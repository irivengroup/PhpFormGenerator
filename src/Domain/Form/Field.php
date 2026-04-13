<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\FieldTypeInterface;
use Iriven\PhpFormGenerator\Domain\Validation\ValidationError;

final class Field
{
    /** @var list<ValidationError> */
    private array $errors = [];

    /** @param array<string, mixed> $options */
    public function __construct(
        private readonly string $name,
        private readonly FieldTypeInterface $type,
        private array $options = [],
        private mixed $data = null
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): FieldTypeInterface
    {
        return $this->type;
    }

    /** @return array<string, mixed> */
    public function options(): array
    {
        return $this->options;
    }

    public function option(string $name, mixed $default = null): mixed
    {
        return $this->options[$name] ?? $default;
    }

    public function data(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    /** @param list<ValidationError> $errors */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /** @return list<ValidationError> */
    public function errors(): array
    {
        return $this->errors;
    }
}
