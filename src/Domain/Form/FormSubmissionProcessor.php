<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\DataTransformerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;
use Iriven\PhpFormGenerator\Domain\Event\PostSubmitEvent;
use Iriven\PhpFormGenerator\Domain\Event\PreSubmitEvent;
use Iriven\PhpFormGenerator\Domain\Event\SubmitEvent;
use Iriven\PhpFormGenerator\Infrastructure\Options\OptionsResolver;

final class FormSubmissionProcessor
{
    public function __construct(
        private readonly FormValidationProcessor $validationProcessor = new FormValidationProcessor(),
        private readonly FormDataMappingProcessor $mappingProcessor = new FormDataMappingProcessor(),
    ) {
    }

    public function handleRequest(Form $form, RequestInterface $request): void
    {
        if (strtoupper((string) ($form->options()['method'] ?? 'POST')) !== $request->getMethod()) {
            return;
        }

        $payload = $request->get($form->getName(), []);
        if (!is_array($payload)) {
            return;
        }

        $form->setSubmitted(true);

        $preSubmit = new PreSubmitEvent($form, $payload);
        $form->dispatch('form.pre_submit', $preSubmit);
        $payload = is_array($preSubmit->getData()) ? $preSubmit->getData() : $payload;

        $this->validateCsrf($form, $payload);

        foreach ($form->fields() as $name => $field) {
            $raw = $payload[$name] ?? null;
            $form->setSubmittedValue($name, $this->submitField($form, $field, $raw, $name));
        }

        $this->validationProcessor->validateFormConstraints($form);

        $form->dispatch('form.submit', new SubmitEvent($form, $form->submittedValues(), ['payload' => $payload]));

        if ($form->isCurrentlyValid()) {
            $this->mappingProcessor->map($form);
        }

        $form->dispatch('form.post_submit', new PostSubmitEvent($form, $form->rawData(), ['valid' => $form->isCurrentlyValid()]));
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function validateCsrf(Form $form, array $payload): void
    {
        if (($form->options()['csrf_protection'] ?? false) !== true) {
            return;
        }

        $tokenField = (string) ($form->options()['csrf_field_name'] ?? '_token');
        $tokenId = (string) ($form->options()['csrf_token_id'] ?? $form->getName());
        $csrfManager = $form->options()['csrf_manager'] ?? null;

        if ($csrfManager !== null && !$csrfManager->isTokenValid($tokenId, is_string($payload[$tokenField] ?? null) ? $payload[$tokenField] : null)) {
            $form->appendError('_form', 'Invalid CSRF token.');
            $form->setValid(false);
        }
    }

    private function submitField(Form $form, FieldConfig $field, mixed $raw, string $path): mixed
    {
        if ($field->collection) {
            return $this->submitCollection($form, $field, is_array($raw) ? $raw : [], $path);
        }

        if ($field->compound) {
            return $this->submitCompound($form, $field, is_array($raw) ? $raw : [], $path);
        }

        if ($field->typeClass === 'Iriven\\PhpFormGenerator\\Domain\\Field\\CheckboxType' && $raw === null) {
            $raw = false;
        }

        $value = $this->reverseTransform($field->transformers, $raw);

        if (is_a($field->typeClass, 'Iriven\\PhpFormGenerator\\Domain\\Field\\CaptchaType', true)) {
            $this->validationProcessor->validateCaptchaField($form, $field, is_string($raw) ? $raw : null, $path);
        }

        $this->validationProcessor->applyConstraintErrors($form, $path, $value, $field->constraints);

        return $value;
    }

    /**
     * @param array<int, DataTransformerInterface> $transformers
     */
    private function reverseTransform(array $transformers, mixed $raw): mixed
    {
        $value = $raw;
        foreach ($transformers as $transformer) {
            $value = $transformer->reverseTransform($value);
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $raw
     * @return array<string, mixed>
     */
    private function submitCompound(Form $form, FieldConfig $field, array $raw, string $path): array
    {
        $result = [];
        foreach ($field->children as $childName => $child) {
            $result[$childName] = $this->submitField($form, $child, $raw[$childName] ?? null, $path . '.' . $childName);
        }

        $this->validationProcessor->applyConstraintErrors($form, $path, $result, $field->constraints);

        return $result;
    }

    /**
     * @param array<int|string, mixed> $raw
     * @return array<int, mixed>
     */
    private function submitCollection(Form $form, FieldConfig $field, array $raw, string $path): array
    {
        $items = [];
        $entryType = $field->entryType;

        foreach ($raw as $index => $row) {
            if (!is_array($row)) {
                $row = ['value' => $row];
            }

            if ($entryType !== null && is_subclass_of($entryType, FormTypeInterface::class)) {
                $items[] = $this->submitFormTypeCollectionEntry($form, $field, $row, $path, (string) $index);
                continue;
            }

            if ($entryType !== null && class_exists($entryType)) {
                /** @var array<int, DataTransformerInterface> $transformers */
                $transformers = method_exists($entryType, 'defaultTransformers') ? $entryType::defaultTransformers() : [];
                $entryField = new FieldConfig((string) $index, $entryType, $field->entryOptions, $field->constraints, $transformers);
                $items[] = $this->submitField($form, $entryField, $row, $path . '.' . (string) $index);
                continue;
            }

            $items[] = $row;
        }

        if (($field->options['allow_delete'] ?? false) !== true) {
            $items = array_values($items);
        }

        $this->validationProcessor->applyConstraintErrors($form, $path, $items, $field->constraints);

        return $items;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function submitFormTypeCollectionEntry(Form $form, FieldConfig $field, array $row, string $path, string $index): array
    {
        $builder = new FormBuilder($field->name . '_entry', null, $field->entryOptions + ['event_dispatcher' => $form->eventDispatcher()]);
        $entryTypeClass = (string) $field->entryType;
        $type = new $entryTypeClass();
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $resolved = $resolver->resolve($field->entryOptions);
        $type->buildForm($builder, $resolved);

        $entryValues = [];
        foreach ($builder->all() as $childName => $child) {
            $entryValues[$childName] = $this->submitField($form, $child, $row[$childName] ?? null, $path . '.' . $index . '.' . $childName);
        }

        return $entryValues;
    }
}
