<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Schema;

use Iriven\PhpFormGenerator\Domain\Contract\SchemaExporterInterface;
use Iriven\PhpFormGenerator\Domain\Form\FieldConfig;
use Iriven\PhpFormGenerator\Domain\Form\Form;
use Iriven\PhpFormGenerator\Infrastructure\Type\TypeResolver;

final class ArraySchemaExporter implements SchemaExporterInterface
{
    /**
     * @return array<string, mixed>
     */
    public function export(Form $form): array
    {
        $fields = [];

        foreach ($form->fields() as $name => $field) {
            $fields[$name] = $this->exportField($field);
        }

        return [
            'name' => $form->getName(),
            'method' => (string) ($form->options()['method'] ?? 'POST'),
            'action' => $form->options()['action'] ?? null,
            'fields' => $fields,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function exportField(FieldConfig $field): array
    {
        return [
            'type' => $this->resolvedFieldType($field),
            'label' => $field->options['label'] ?? null,
            'required' => (bool) ($field->options['required'] ?? false),
            'help' => $field->options['help'] ?? null,
            'placeholder' => $this->placeholder($field),
            'default' => $field->options['data'] ?? null,
            'choices' => $this->choices($field),
            'constraints' => $this->constraintClasses($field),
            'compound' => $field->compound,
            'collection' => $field->collection,
            'entry_type' => $this->resolvedEntryType($field),
            'entry_options' => $field->entryOptions,
            'children' => $this->children($field),
        ];
    }

    private function resolvedFieldType(FieldConfig $field): string
    {
        return TypeResolver::resolveFieldType($field->typeClass);
    }

    private function resolvedEntryType(FieldConfig $field): ?string
    {
        $entryType = $field->entryType;

        if (!is_string($entryType) || $entryType === '') {
            return null;
        }

        return TypeResolver::resolveFieldType($entryType);
    }

    private function placeholder(FieldConfig $field): mixed
    {
        return is_array($field->options['attr'] ?? null)
            ? ($field->options['attr']['placeholder'] ?? null)
            : null;
    }

    /**
     * @return array<int|string, mixed>
     */
    private function choices(FieldConfig $field): array
    {
        return is_array($field->options['choices'] ?? null)
            ? $field->options['choices']
            : [];
    }

    /**
     * @return array<int, string>
     */
    private function constraintClasses(FieldConfig $field): array
    {
        return array_map(
            static fn (object $constraint): string => $constraint::class,
            $field->constraints
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function children(FieldConfig $field): array
    {
        $children = [];

        foreach ($field->children as $name => $child) {
            $children[$name] = $this->exportField($child);
        }

        return $children;
    }
}
