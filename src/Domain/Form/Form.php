<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\DataMapperInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FormInterface;
use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;
use Iriven\PhpFormGenerator\Domain\Validation\ValidationError;
use Iriven\PhpFormGenerator\Domain\Validation\ValidatorEngine;

final class Form implements FormInterface
{
    /** @var array<string, Field> */
    private array $fields;
    private bool $submitted = false;
    private bool $valid = false;
    /** @var list<ValidationError> */
    private array $errors = [];
    private mixed $data;

    /**
     * @param array<string, Field> $fields
     * @param array<string, mixed> $options
     */
    public function __construct(
        array $fields,
        private readonly array $options,
        private readonly DataMapperInterface $dataMapper,
        private readonly CsrfManagerInterface $csrfManager,
        private readonly ValidatorEngine $validator,
        mixed $data = null
    ) {
        $this->fields = $fields;
        $this->data = $data;

        if ($data !== null) {
            $mapped = $this->dataMapper->mapDataToFields($data, array_keys($fields));
            foreach ($mapped as $name => $value) {
                if (isset($this->fields[$name])) {
                    $this->fields[$name]->setData($value);
                }
            }
        }
    }

    public function handleRequest(RequestInterface $request): void
    {
        $requestMethod = strtoupper($request->method());
        $formMethod = strtoupper((string) ($this->options['method'] ?? 'POST'));
        if ($requestMethod !== $formMethod) {
            return;
        }

        $this->submitted = true;
        $submitted = [];

        foreach ($this->fields as $name => $field) {
            $raw = $field->option('type') === 'file' ? ($request->files()[$name] ?? null) : $request->get($name);
            $normalized = $field->type()->normalizeSubmittedValue($raw, $field->options());
            $field->setData($normalized);
            $errors = $this->validator->validate($normalized, $field->option('constraints', []), ['field' => $name]);
            $field->setErrors($errors);
            $submitted[$name] = $normalized;
        }

        if (($this->options['csrf_protection'] ?? true) === true) {
            $tokenField = (string) ($this->options['csrf_field_name'] ?? '_token');
            $tokenId = (string) ($this->options['csrf_token_id'] ?? 'form');
            $token = $request->get($tokenField);
            if (!$this->csrfManager->isTokenValid($tokenId, is_string($token) ? $token : null)) {
                $this->errors[] = new ValidationError('Invalid CSRF token.');
            }
        }

        $this->data = $this->dataMapper->mapFieldsToData($submitted, $this->data);
        $this->valid = count($this->getErrors()) === 0;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    public function isValid(): bool
    {
        return $this->submitted && $this->valid;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function createView(): FormView
    {
        $children = [];
        foreach ($this->fields as $field) {
            $children[] = array_merge([
                'name' => $field->name(),
                'errors' => array_map(static fn (ValidationError $e) => $e->message, $field->errors()),
                'data' => $field->data(),
                'options' => $field->options(),
            ], $field->type()->buildViewVariables($field));
        }

        if (($this->options['csrf_protection'] ?? true) === true) {
            $children[] = [
                'name' => (string) ($this->options['csrf_field_name'] ?? '_token'),
                'type' => 'hidden',
                'label' => null,
                'value' => $this->csrfManager->generateToken((string) ($this->options['csrf_token_id'] ?? 'form')),
                'errors' => [],
                'options' => [],
                'choices' => [],
                'attributes' => [],
            ];
        }

        return new FormView(
            name: (string) ($this->options['name'] ?? 'form'),
            method: strtoupper((string) ($this->options['method'] ?? 'POST')),
            action: (string) ($this->options['action'] ?? ''),
            attributes: (array) ($this->options['attr'] ?? []),
            children: $children,
            errors: array_map(static fn (ValidationError $e) => $e->message, $this->errors)
        );
    }

    public function getErrors(bool $deep = true): array
    {
        $errors = $this->errors;
        if ($deep) {
            foreach ($this->fields as $field) {
                foreach ($field->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }

        return $errors;
    }
}
