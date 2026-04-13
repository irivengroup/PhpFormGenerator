<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;

final class Form
{
    /** @var list<FieldDefinition> */
    private array $fields = [];

    /** @var list<Fieldset> */
    private array $fieldsets = [];

    private bool $submitted = false;
    private bool $valid = true;
    private array $errors = [];

    public function __construct(
        private readonly string $name,
        private readonly array $options = [],
        private readonly ?CsrfManagerInterface $csrfManager = null,
    ) {
    }

    public function addField(FieldDefinition $field): void
    {
        $this->fields[] = $field;
    }

    public function addFieldset(Fieldset $fieldset): void
    {
        $this->fieldsets[] = $fieldset;
    }

    public function handleRequest(RequestInterface $request): void
    {
        $method = strtoupper((string) ($this->options['method'] ?? 'POST'));
        if (strtoupper($request->method()) !== $method) {
            return;
        }

        $this->submitted = true;
        $this->valid = true;
        $this->errors = [];

        if (($this->options['csrf_protection'] ?? false) === true && $this->csrfManager !== null) {
            $tokenField = (string) ($this->options['csrf_field_name'] ?? '_token');
            $tokenId = (string) ($this->options['csrf_token_id'] ?? $this->name);
            $token = $request->input($tokenField);
            if (!$this->csrfManager->isTokenValid($tokenId, is_string($token) ? $token : null)) {
                $this->valid = false;
                $this->errors[] = 'Invalid CSRF token.';
            }
        }

        foreach ($this->fields as $field) {
            $field->value = $request->input($field->name, $field->value);
            $field->errors = [];
            foreach ($field->constraints as $constraint) {
                foreach ($constraint->validate($field->value) as $error) {
                    $field->errors[] = $error;
                    $this->valid = false;
                }
            }
        }
    }

    /** @return list<FieldDefinition> */
    public function fields(): array
    {
        return $this->fields;
    }

    /** @return list<Fieldset> */
    public function fieldsets(): array
    {
        return $this->fieldsets;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    public function isValid(): bool
    {
        return $this->submitted && $this->valid;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function options(): array
    {
        return $this->options;
    }

    public function csrfToken(): ?string
    {
        if (($this->options['csrf_protection'] ?? false) !== true || $this->csrfManager === null) {
            return null;
        }

        return $this->csrfManager->generateToken((string) ($this->options['csrf_token_id'] ?? $this->name));
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
