<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\DataMapperInterface;
use Iriven\PhpFormGenerator\Domain\Contract\EventDispatcherInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;
use Iriven\PhpFormGenerator\Domain\Event\FormEvent;
use Iriven\PhpFormGenerator\Domain\Event\FormEvents;
use Iriven\PhpFormGenerator\Infrastructure\Mapping\ArrayDataMapper;

final class Form
{
    /** @var array<string,FieldDefinition> */
    private array $fields = [];

    /** @var list<string> */
    private array $fieldOrder = [];

    /** @var list<Fieldset> */
    private array $fieldsets = [];

    /** @var list<callable(array<string,mixed>): list<string>> */
    private array $formConstraints = [];

    /** @var list<string> */
    private array $errors = [];

    private bool $submitted = false;
    private bool $valid = true;
    private mixed $data = null;

    private ?Form $parent = null;
    private ?string $parentFieldName = null;
    private ?FieldDefinition $ownerField = null;

    public function __construct(
        private readonly string $name,
        private readonly array $options = [],
        private readonly ?CsrfManagerInterface $csrfManager = null,
        private readonly ?EventDispatcherInterface $dispatcher = null,
        private readonly ?DataMapperInterface $dataMapper = null,
    ) {
    }

    public function setParent(Form $parent, string $fieldName, FieldDefinition $ownerField): void
    {
        $this->parent = $parent;
        $this->parentFieldName = $fieldName;
        $this->ownerField = $ownerField;
    }

    public function parent(): ?Form
    {
        return $this->parent;
    }


    public function csrfManager(): ?CsrfManagerInterface
    {
        return $this->csrfManager;
    }

    public function dispatcher(): ?EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function dataMapper(): ?DataMapperInterface
    {
        return $this->dataMapper;
    }


    public function ownerField(): ?FieldDefinition
    {
        return $this->ownerField;
    }

    public function addField(FieldDefinition $field): void
    {
        $this->fields[$field->name] = $field;
        $this->fieldOrder[] = $field->name;
    }

    public function addFieldset(Fieldset $fieldset): void
    {
        $this->fieldsets[] = $fieldset;
    }

    public function addFormConstraint(callable $constraint): void
    {
        $this->formConstraints[] = $constraint;
    }

    /**
     * @return list<FieldDefinition>
     */
    public function fields(): array
    {
        return array_values(array_map(fn(string $name) => $this->fields[$name], $this->fieldOrder));
    }

    /** @return list<Fieldset> */
    public function fieldsets(): array
    {
        return $this->fieldsets;
    }

    public function field(string $name): ?FieldDefinition
    {
        return $this->fields[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    public function get(string $name): FieldDefinition
    {
        if (!isset($this->fields[$name])) {
            throw new \InvalidArgumentException(sprintf('Field "%s" does not exist.', $name));
        }

        return $this->fields[$name];
    }

    /**
     * @return array<string,FieldDefinition>
     */
    public function all(): array
    {
        return $this->fields;
    }

    public function name(): string
    {
        return $this->name;
    }

    /** @return array<string,mixed> */
    public function options(): array
    {
        return $this->options;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
        $this->dispatcher?->dispatch(FormEvents::PRE_SET_DATA, new FormEvent($this, $data));
        $this->synchronizeInitialData($data);
    }

    private function synchronizeInitialData(mixed $data): void
    {
        foreach ($this->fields() as $field) {
            $value = $this->extractValue($data, $field->name, $field->options['data'] ?? null);

            if ($field->isCollection()) {
                $values = is_array($value) ? $value : [];
                $this->initializeCollectionField($field, $values);
                continue;
            }

            if ($field->isCompound()) {
                if ($field->compoundForm() === null) {
                    continue;
                }

                $field->compoundForm()?->setData($value);
                continue;
            }

            $field->value = $value;
        }
    }

    public function handleRequest(RequestInterface $request): void
    {
        $method = strtoupper((string) ($this->options['method'] ?? 'POST'));
        if (strtoupper($request->method()) !== $method) {
            return;
        }

        $payload = $request->all();
        $files = $request->files();

        if ($this->parent === null) {
            $payload = isset($payload[$this->name]) && is_array($payload[$this->name]) ? $payload[$this->name] : $payload;
            $files = isset($files[$this->name]) && is_array($files[$this->name]) ? $files[$this->name] : $files;
        }

        $this->submit($payload, $files, true);
    }

    /**
     * @param array<string,mixed> $payload
     * @param array<string,mixed> $files
     */
    public function submit(array $payload, array $files = [], bool $markSubmitted = true): void
    {
        if ($markSubmitted) {
            $this->submitted = true;
        }

        $this->valid = true;
        $this->errors = [];
        foreach ($this->fields() as $field) {
            $field->errors = [];
        }

        $this->dispatcher?->dispatch(FormEvents::PRE_SUBMIT, new FormEvent($this, $payload));

        if ($this->parent === null && ($this->options['csrf_protection'] ?? false) === true && $this->csrfManager !== null) {
            $tokenField = (string) ($this->options['csrf_field_name'] ?? '_token');
            $tokenId = (string) ($this->options['csrf_token_id'] ?? $this->name);
            $token = $payload[$tokenField] ?? null;
            if (!$this->csrfManager->isTokenValid($tokenId, is_string($token) ? $token : null)) {
                $this->valid = false;
                $this->errors[] = 'Invalid CSRF token.';
            }
        }

        $normalizedData = [];

        foreach ($this->fields() as $field) {
            if ($field->isCollection()) {
                $normalizedData[$field->name] = $this->submitCollectionField(
                    $field,
                    isset($payload[$field->name]) && is_array($payload[$field->name]) ? $payload[$field->name] : [],
                    isset($files[$field->name]) && is_array($files[$field->name]) ? $files[$field->name] : []
                );
                continue;
            }

            if ($field->isCompound()) {
                $childPayload = isset($payload[$field->name]) && is_array($payload[$field->name]) ? $payload[$field->name] : [];
                $childFiles = isset($files[$field->name]) && is_array($files[$field->name]) ? $files[$field->name] : [];
                $field->compoundForm()?->submit($childPayload, $childFiles, true);
                if ($field->compoundForm()?->hasErrors(true)) {
                    $this->valid = false;
                }

                if ($field->mapped()) {
                    $normalizedData[$field->name] = $field->compoundForm()?->data();
                }

                continue;
            }

            $value = $this->normalizeScalarFieldSubmission($field, $payload, $files);
            $field->value = $value;
            $this->validateField($field, $normalizedData);

            if ($field->mapped()) {
                $normalizedData[$field->name] = $field->value;
            }
        }

        foreach ($this->formConstraints as $constraint) {
            foreach ($constraint($normalizedData) as $error) {
                $this->errors[] = (string) $error;
                $this->valid = false;
            }
        }

        $this->dispatcher?->dispatch(FormEvents::SUBMIT, new FormEvent($this, $normalizedData));

        $mapper = $this->dataMapper ?? new ArrayDataMapper();
        $this->data = $mapper->map($normalizedData, $this->data);

        if (!$this->valid) {
            $this->dispatcher?->dispatch(FormEvents::VALIDATION_ERROR, new FormEvent($this, $normalizedData));
        }

        $this->dispatcher?->dispatch(FormEvents::POST_SUBMIT, new FormEvent($this, $this->data));
    }

    /**
     * @param array<string,mixed> $payload
     * @param array<string,mixed> $files
     */
    private function normalizeScalarFieldSubmission(FieldDefinition $field, array $payload, array $files): mixed
    {
        if ($field->type->renderType() === 'checkbox') {
            return array_key_exists($field->name, $payload)
                ? ($field->options['checked_value'] ?? '1')
                : ($field->options['unchecked_value'] ?? '0');
        }

        if ($field->type->renderType() === 'file') {
            return $files[$field->name] ?? null;
        }

        return $payload[$field->name] ?? $field->value;
    }

    /**
     * @param array<string,mixed> $normalizedData
     */
    private function validateField(FieldDefinition $field, array &$normalizedData): void
    {
        $context = ['field' => $field, 'data' => &$normalizedData, 'form' => $this];

        foreach ($field->constraints as $constraint) {
            foreach ($constraint->validate($field->value, $context) as $error) {
                $field->errors[] = (string) $error;
                $this->valid = false;
            }
        }
    }

    /**
     * @param array<int|string,mixed> $payload
     * @param array<int|string,mixed> $files
     * @return array<int|string,mixed>
     */
    private function submitCollectionField(FieldDefinition $field, array $payload, array $files): array
    {
        $normalized = [];
        $this->initializeCollectionField($field, $payload);

        foreach ($field->entries() as $index => $entryForm) {
            $entryPayload = isset($payload[$index]) && is_array($payload[$index])
                ? $payload[$index]
                : (array_key_exists($index, $payload) ? ['value' => $payload[$index]] : []);
            $entryFiles = isset($files[$index]) && is_array($files[$index]) ? $files[$index] : [];

            $entryForm->submit($entryPayload, $entryFiles, true);
            if ($entryForm->hasErrors(true)) {
                $this->valid = false;
            }

            $entryData = $entryForm->data();
            if ($this->isScalarCollectionEntry($field)) {
                $normalized[$index] = is_array($entryData) ? ($entryData['value'] ?? null) : $entryData;
            } else {
                $normalized[$index] = $entryData;
            }
        }

        if (($field->options['allow_delete'] ?? true) === false) {
            foreach (array_keys($field->entries()) as $index) {
                if (!array_key_exists($index, $payload) && array_key_exists($index, $normalized)) {
                    unset($normalized[$index]);
                }
            }
        }

        $field->value = $normalized;

        return $normalized;
    }

    /**
     * @param array<int|string,mixed> $values
     */
    private function initializeCollectionField(FieldDefinition $field, array $values): void
    {
        $field->clearEntries();

        $entryType = (string) ($field->options['entry_type'] ?? \Iriven\PhpFormGenerator\Domain\Field\TextType::class);
        $entryOptions = (array) ($field->options['entry_options'] ?? []);

        foreach ($values as $index => $value) {
            $entryForm = $this->createCollectionEntryForm($field, $entryType, $entryOptions, $index);
            if ($this->isFormTypeClass($entryType)) {
                $entryForm->setData($value);
            } else {
                $entryField = $entryForm->get('value');
                $entryField->value = $value;
                $entryForm->setData(['value' => $value]);
            }
            $field->setEntry($index, $entryForm);
        }

        if (($field->options['prototype'] ?? true) === true) {
            $field->options['prototype_form'] = $this->createCollectionEntryForm($field, $entryType, $entryOptions, '__name__');
        }
    }

    private function createCollectionEntryForm(
        FieldDefinition $field,
        string $entryType,
        array $entryOptions,
        int|string $index
    ): Form {
        $entryName = (string) $index;
        $entryForm = new Form(
            $entryName,
            ['method' => $this->options['method'] ?? 'POST'],
            $this->csrfManager,
            $this->dispatcher,
            $this->resolveChildMapper($entryOptions)
        );
        $entryForm->setParent($this, $field->name, $field);

        if ($this->isFormTypeClass($entryType)) {
            /** @var FormTypeInterface $type */
            $type = new $entryType();
            $builder = new FormBuilder($entryName, ['method' => $this->options['method'] ?? 'POST'], $this->csrfManager, $this->dispatcher, $this->resolveChildMapper($entryOptions));
            $builder->adoptForm($entryForm);
            $type->buildForm($builder, $entryOptions);
            return $builder->getForm();
        }

        $builder = new FormBuilder($entryName, ['method' => $this->options['method'] ?? 'POST'], $this->csrfManager, $this->dispatcher, $this->resolveChildMapper($entryOptions));
        $builder->adoptForm($entryForm);
        $builder->add('value', $entryType, $entryOptions + ['label' => $entryOptions['label'] ?? ucfirst((string) $index)]);
        return $builder->getForm();
    }

    private function resolveChildMapper(array $options): ?DataMapperInterface
    {
        if (isset($options['data_mapper']) && $options['data_mapper'] instanceof DataMapperInterface) {
            return $options['data_mapper'];
        }

        if (isset($options['data_class']) && is_string($options['data_class']) && $options['data_class'] !== '') {
            return new \Iriven\PhpFormGenerator\Infrastructure\Mapping\ObjectDataMapper($options['data_class']);
        }

        return null;
    }

    private function isFormTypeClass(string $class): bool
    {
        return class_exists($class) && is_subclass_of($class, FormTypeInterface::class);
    }

    private function isScalarCollectionEntry(FieldDefinition $field): bool
    {
        $entryType = (string) ($field->options['entry_type'] ?? '');

        return !$this->isFormTypeClass($entryType);
    }

    private function extractValue(mixed $source, string $key, mixed $default = null): mixed
    {
        if (is_array($source)) {
            return array_key_exists($key, $source) ? $source[$key] : $default;
        }

        if (is_object($source) && (isset($source->{$key}) || property_exists($source, $key))) {
            return $source->{$key};
        }

        return $default;
    }

    public function data(): mixed
    {
        return $this->data;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    public function isValid(): bool
    {
        return $this->submitted && $this->valid && !$this->hasErrors(true);
    }

    public function hasErrors(bool $deep = true): bool
    {
        if ($this->errors !== []) {
            return true;
        }

        foreach ($this->fields() as $field) {
            if ($field->hasErrors($deep)) {
                return true;
            }
        }

        return false;
    }

    /** @return list<string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function csrfToken(): ?string
    {
        if ($this->parent !== null) {
            return null;
        }

        if (($this->options['csrf_protection'] ?? false) !== true || $this->csrfManager === null) {
            return null;
        }

        return $this->csrfManager->generateToken((string) ($this->options['csrf_token_id'] ?? $this->name));
    }

    public function fullNamePrefix(): string
    {
        $segments = [];
        $form = $this;
        while ($form->parent !== null) {
            array_unshift($segments, (string) $form->parentFieldName);
            $form = $form->parent;
        }

        $prefix = $form->name;
        foreach ($segments as $segment) {
            $prefix .= '[' . $segment . ']';
        }

        return $prefix;
    }
}
