<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

final class Fieldset
{
    /** @var list<FieldDefinition> */
    private array $fields = [];

    /** @var list<Fieldset> */
    private array $children = [];

    /** @param array<string,mixed> $options */
    public function __construct(private readonly array $options = [])
    {
    }

    public function addField(FieldDefinition $field): void
    {
        $this->fields[] = $field;
    }

    public function addChild(Fieldset $fieldset): void
    {
        $this->children[] = $fieldset;
    }

    /** @return list<FieldDefinition> */
    public function fields(): array
    {
        return $this->fields;
    }

    /** @return list<Fieldset> */
    public function children(): array
    {
        return $this->children;
    }

    /** @return array<string,mixed> */
    public function options(): array
    {
        return $this->options;
    }

    public function contains(FieldDefinition $candidate): bool
    {
        foreach ($this->fields as $field) {
            if ($field === $candidate) {
                return true;
            }
        }

        foreach ($this->children as $child) {
            if ($child->contains($candidate)) {
                return true;
            }
        }

        return false;
    }
}
