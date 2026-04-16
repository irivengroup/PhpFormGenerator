<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Application\FormHookKernel;
use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;
use Iriven\PhpFormGenerator\Domain\Contract\EventDispatcherInterface;
use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;
use Iriven\PhpFormGenerator\Domain\Event\PreSetDataEvent;
use Iriven\PhpFormGenerator\Infrastructure\PropertyAccess\PropertyAccessor;

final class Form
{
    private bool $submitted = false;
    private bool $valid = true;

    /** @var array<string, mixed> */
    private array $values = [];

    /** @var array<string, array<int, string>> */
    private array $errors = [];

    /** @var array<int, ConstraintInterface> */
    private array $formConstraints = [];

    private PropertyAccessor $accessor;
    private FormSubmissionProcessor $submissionProcessor;
    private FormViewBuilder $viewBuilder;

    /**
     * @param array<string, FieldConfig> $fields
     * @param array<string, mixed> $options
     * @param array<int, Fieldset> $fieldsets
     * @param array<int, ConstraintInterface> $formConstraints
     */
    public function __construct(
        private readonly string $name,
        private readonly array $fields,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly array $options = [],
        private mixed $data = null,
        private readonly array $fieldsets = [],
        array $formConstraints = [],
    ) {
        $this->formConstraints = $formConstraints;
        $this->accessor = new PropertyAccessor();
        $this->submissionProcessor = new FormSubmissionProcessor();
        $this->viewBuilder = new FormViewBuilder();

        $this->dispatch('form.pre_set_data', new PreSetDataEvent($this, $this->data));
        $this->initializeValues();
        $this->dispatchHook('post_build', ['data' => $this->data]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function handleRequest(RequestInterface $request): void
    {
        $this->dispatchHook('pre_handle_request', ['request' => $request]);
        $this->submissionProcessor->handleRequest($this, $request);
        $this->dispatchHook('post_handle_request', [
            'request' => $request,
            'submitted' => $this->submitted,
            'valid' => $this->valid,
        ]);
    }

    public function getData(): mixed
    {
        return $this->data ?? $this->values;
    }

    /** @return array<string, mixed> */
    public function getSubmittedValues(): array
    {
        return $this->values;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    public function isValid(): bool
    {
        return $this->submitted && $this->valid;
    }

    public function isCurrentlyValid(): bool
    {
        return $this->valid;
    }

    /** @return array<string, array<int, string>> */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function createView(): FormView
    {
        return $this->viewBuilder->build($this);
    }

    /** @return array<string, FieldConfig> */
    public function fields(): array
    {
        return $this->fields;
    }

    /** @return array<string, mixed> */
    public function options(): array
    {
        return $this->options;
    }

    /** @return array<int, Fieldset> */
    public function fieldsets(): array
    {
        return $this->fieldsets;
    }

    /** @return array<string, mixed> */
    public function submittedValues(): array
    {
        return $this->values;
    }

    /** @return array<string, array<int, string>> */
    public function errors(): array
    {
        return $this->errors;
    }

    /** @return array<int, ConstraintInterface> */
    public function formConstraints(): array
    {
        return $this->formConstraints;
    }

    public function eventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function rawData(): mixed
    {
        return $this->data;
    }

    public function replaceData(mixed $data): void
    {
        $this->data = $data;
    }

    public function setSubmitted(bool $submitted): void
    {
        $this->submitted = $submitted;
    }

    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    public function setSubmittedValue(string $name, mixed $value): void
    {
        $this->values[$name] = $value;
    }

    /** @param array<int, string> $errors */
    public function setErrorsForPath(string $path, array $errors): void
    {
        $this->errors[$path] = $errors;
    }

    public function appendError(string $path, string $error): void
    {
        $this->errors[$path][] = $error;
    }

    public function dispatch(string $eventName, object $event): void
    {
        $this->eventDispatcher->dispatch($eventName, $event);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function dispatchHook(string $hookName, array $context = []): void
    {
        $kernel = $this->options['hook_kernel'] ?? null;

        if ($kernel instanceof FormHookKernel) {
            $kernel->dispatch($hookName, $this, $context);
        }
    }

    private function initializeValues(): void
    {
        foreach ($this->fields as $name => $field) {
            $raw = $this->readDataValue($name, $field);

            if ($field->collection || $field->compound) {
                $this->values[$name] = is_array($raw) ? $raw : [];
                continue;
            }

            $value = $raw;
            foreach ($field->transformers as $transformer) {
                $value = $transformer->transform($value);
            }
            $this->values[$name] = $value;
        }
    }

    private function readDataValue(string $name, FieldConfig $field): mixed
    {
        if (array_key_exists('data', $field->options)) {
            return $field->options['data'];
        }

        if (is_array($this->data)) {
            return $this->data[$name] ?? null;
        }

        if (is_object($this->data)) {
            return $this->accessor->getValue($this->data, $name);
        }

        return null;
    }
}
