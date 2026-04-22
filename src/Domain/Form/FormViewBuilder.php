<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Form;

use Iriven\Fluxon\Domain\Contract\CaptchaManagerInterface;
use Iriven\Fluxon\Domain\Contract\EventDispatcherInterface;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Infrastructure\Options\OptionsResolver;
use Iriven\Fluxon\Presentation\Html\CaptchaSvgRenderer;

final class FormViewBuilder
{
    public function __construct(
        private readonly FormViewFactory $factory = new FormViewFactory(),
    ) {
    }

    public function build(Form $form): FormView
    {
        $children = [];
        foreach ($form->fields() as $name => $field) {
            $fullName = $form->getName() . '[' . $name . ']';
            $children[] = $this->createFieldView($form, $name, $field, $fullName, 'form_' . $name, $form->submittedValues()[$name] ?? null, $name);
        }

        $vars = [
            'method' => strtoupper((string) ($form->options()['method'] ?? 'POST')),
            'action' => (string) ($form->options()['action'] ?? ''),
            'attr' => $form->options()['attr'] ?? [],
            'csrf_protection' => (bool) ($form->options()['csrf_protection'] ?? false),
        ];

        if (($form->options()['csrf_protection'] ?? false) === true) {
            $csrfManager = $form->options()['csrf_manager'] ?? null;
            $tokenField = (string) ($form->options()['csrf_field_name'] ?? '_token');
            $tokenId = (string) ($form->options()['csrf_token_id'] ?? $form->getName());
            if ($csrfManager !== null) {
                $children[] = $this->factory->create(
                    $tokenField,
                    $form->getName() . '[' . $tokenField . ']',
                    'form_' . $tokenField,
                    'hidden',
                    $csrfManager->generateToken($tokenId),
                    ['label' => $tokenField, 'type_class' => 'hidden'],
                    [],
                    $form->errors()[$tokenField] ?? [],
                );
            }
        }

        return $this->factory->create(
            $form->getName(),
            $form->getName(),
            $form->getName(),
            'form',
            null,
            $vars,
            $children,
            $form->errors()['_form'] ?? [],
            $form->fieldsets(),
            $form->isSubmitted(),
            $form->isSubmitted() && $form->isValid(),
        );
    }

    private function createFieldView(Form $form, string $name, FieldConfig $field, string $fullName, string $id, mixed $value, string $errorPath): FormView
    {
        $vars = $field->options;
        $vars['label'] = $field->options['label'] ?? ucfirst($name);
        $vars['attr'] = $field->options['attr'] ?? [];
        $vars['type_class'] = $field->typeClass;

        $this->hydrateCaptchaViewVars($form, $name, $field, $id, $vars);

        if ($field->collection) {
            return $this->buildCollectionView($form, $name, $field, $fullName, $id, $value, $errorPath, $vars);
        }

        if ($field->compound) {
            $children = [];
            foreach ($field->children as $childName => $child) {
                $children[] = $this->createFieldView(
                    $form,
                    $childName,
                    $child,
                    $fullName . '[' . $childName . ']',
                    $id . '_' . $childName,
                    is_array($value) ? ($value[$childName] ?? null) : null,
                    $errorPath . '.' . $childName,
                );
            }

            return $this->factory->create($name, $fullName, $id, 'compound', $value, $vars, $children, $form->errors()[$errorPath] ?? []);
        }

        return $this->factory->create($name, $fullName, $id, $field->typeClass, $value, $vars, [], $form->errors()[$errorPath] ?? []);
    }

    /** @param array<string,mixed> $vars */
    private function hydrateCaptchaViewVars(Form $form, string $name, FieldConfig $field, string $id, array &$vars): void
    {
        if (!is_a($field->typeClass, 'Iriven\\Fluxon\\Domain\\Field\\CaptchaType', true)) {
            return;
        }

        $captchaManager = $form->options()['captcha_manager'] ?? null;
        if (!$captchaManager instanceof CaptchaManagerInterface) {
            return;
        }

        $minLength = max(5, (int) ($field->options['min_length'] ?? 5));
        $maxLength = min(8, max($minLength, (int) ($field->options['max_length'] ?? 8)));
        $captchaKey = $form->getName() . '.' . $name;
        $code = $captchaManager->generateCode($captchaKey, $minLength, $maxLength);

        $vars['captcha_key'] = $captchaKey;
        $vars['captcha_svg'] = (new CaptchaSvgRenderer())->render($code, $id . '_captcha');
        $vars['help'] = $field->options['help'] ?? sprintf('Enter the case-sensitive code shown above (%d to %d characters).', $minLength, $maxLength);
    }

    /**
     * @param array<string,mixed> $vars
     */
    private function buildCollectionView(Form $form, string $name, FieldConfig $field, string $fullName, string $id, mixed $value, string $errorPath, array $vars): FormView
    {
        $children = [];
        if (is_array($value)) {
            foreach ($value as $index => $row) {
                $entryChildren = $this->buildCollectionEntryChildren($form, $name, $field, $fullName, $id, $row, $errorPath, (string) $index);
                $children[] = $this->factory->create(
                    (string) $index,
                    $fullName . '[' . $index . ']',
                    $id . '_' . $index,
                    'collection_entry',
                    $row,
                    ['label' => (string) $index],
                    $entryChildren,
                    $form->errors()[$errorPath . '.' . (string) $index] ?? [],
                );
            }
        }

        if (($field->options['prototype'] ?? false) === true && $field->entryType !== null && is_subclass_of($field->entryType, FormTypeInterface::class)) {
            $vars['prototype_view'] = $this->buildPrototypeView($form, $name, $field, $fullName, $id, $errorPath);
        }

        return $this->factory->create($name, $fullName, $id, 'collection', $value, $vars, $children, $form->errors()[$errorPath] ?? []);
    }

    /**
     * @return array<int, FormView>
     */
    private function buildCollectionEntryChildren(Form $form, string $name, FieldConfig $field, string $fullName, string $id, mixed $row, string $errorPath, string $index): array
    {
        $entryChildren = [];
        if ($field->entryType === null || !is_subclass_of($field->entryType, FormTypeInterface::class)) {
            return $entryChildren;
        }

        $builder = new FormBuilder($name . '_entry', null, $field->entryOptions + ['event_dispatcher' => $form->eventDispatcher()]);
        $entryTypeClass = $field->entryType;
        $entry = new $entryTypeClass();
        $resolver = new OptionsResolver();
        $entry->configureOptions($resolver);
        $resolved = $resolver->resolve($field->entryOptions);
        $entry->buildForm($builder, $resolved);

        foreach ($builder->all() as $childName => $child) {
            $entryChildren[] = $this->createFieldView(
                $form,
                $childName,
                $child,
                $fullName . '[' . $index . '][' . $childName . ']',
                $id . '_' . $index . '_' . $childName,
                is_array($row) ? ($row[$childName] ?? null) : null,
                $errorPath . '.' . $index . '.' . $childName,
            );
        }

        return $entryChildren;
    }

    private function buildPrototypeView(Form $form, string $name, FieldConfig $field, string $fullName, string $id, string $errorPath): FormView
    {
        $builder = new FormBuilder($name . '_prototype', null, $field->entryOptions + ['event_dispatcher' => $form->eventDispatcher()]);
        $entryTypeClass = (string) $field->entryType;
        $entry = new $entryTypeClass();
        $resolver = new OptionsResolver();
        $entry->configureOptions($resolver);
        $resolved = $resolver->resolve($field->entryOptions);
        $entry->buildForm($builder, $resolved);

        $prototypeChildren = [];
        foreach ($builder->all() as $childName => $child) {
            $prototypeChildren[] = $this->createFieldView(
                $form,
                $childName,
                $child,
                $fullName . '[__name__][' . $childName . ']',
                $id . '__name__' . '_' . $childName,
                null,
                $errorPath . '.__name__.' . $childName,
            );
        }

        return $this->factory->create('__name__', $fullName . '[__name__]', $id . '__name__', 'collection_entry', null, [], $prototypeChildren, []);
    }

}
