<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FieldTypeInterface;

final class FieldDefinition
{
    /** @var list<ConstraintInterface> */
    public readonly array $constraints;

    /** @var array<int|string, Form> */
    private array $entries = [];

    private ?Form $compoundForm = null;

    /** @var list<string> */
    public array $errors = [];

    public mixed $value;

    /**
     * @param list<ConstraintInterface> $constraints
     * @param array<string,mixed> $options
     */
    public function __construct(
        public readonly string $name,
        public readonly FieldTypeInterface $type,
        array $options = [],
        array $constraints = [],
        mixed $value = null,
    ) {
        $this->options = $options;
        $this->constraints = $constraints;
        $this->value = $value;
    }

    /** @var array<string,mixed> */
    public array $options = [];

    public function label(): string
    {
        return (string) ($this->options['label'] ?? ucfirst(str_replace(['_', '.'], ' ', $this->name)));
    }

    public function id(string $prefix = ''): string
    {
        $base = (string) ($this->options['id'] ?? $this->name);
        if ($prefix === '') {
            return $base;
        }

        return trim($prefix . '_' . $base, '_');
    }

    public function mapped(): bool
    {
        return (bool) ($this->options['mapped'] ?? true);
    }

    public function isCompound(): bool
    {
        return $this->type->isCompound();
    }

    public function isCollection(): bool
    {
        return $this->type->isCollection();
    }

    public function setCompoundForm(Form $form): void
    {
        $this->compoundForm = $form;
    }

    public function compoundForm(): ?Form
    {
        return $this->compoundForm;
    }

    /**
     * @return array<int|string, Form>
     */
    public function entries(): array
    {
        return $this->entries;
    }

    public function setEntry(int|string $index, Form $form): void
    {
        $this->entries[$index] = $form;
    }

    public function clearEntries(): void
    {
        $this->entries = [];
    }

    public function hasErrors(bool $deep = true): bool
    {
        if ($this->errors !== []) {
            return true;
        }

        if (!$deep) {
            return false;
        }

        if ($this->compoundForm !== null && $this->compoundForm->hasErrors(true)) {
            return true;
        }

        foreach ($this->entries as $entry) {
            if ($entry->hasErrors(true)) {
                return true;
            }
        }

        return false;
    }
}
