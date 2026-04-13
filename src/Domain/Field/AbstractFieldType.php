<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

use Iriven\PhpFormGenerator\Domain\Contract\FieldTypeInterface;
use Iriven\PhpFormGenerator\Domain\Form\Field;

abstract class AbstractFieldType implements FieldTypeInterface
{
    public function configureOptions(array $options = []): array
    {
        return array_merge([
            'label' => null,
            'required' => false,
            'constraints' => [],
            'attr' => [],
            'choices' => [],
        ], $options);
    }

    public function buildField(string $name, array $options = []): Field
    {
        return new Field($name, $this, $this->configureOptions($options), $options['data'] ?? null);
    }

    public function normalizeSubmittedValue(mixed $value, array $options = []): mixed
    {
        return $value;
    }

    public function buildViewVariables(Field $field): array
    {
        return [
            'type' => $field->option('type', $this->name()),
            'label' => $field->option('label') ?? ucfirst($field->name()),
            'value' => $field->data(),
            'choices' => $field->option('choices', []),
            'attributes' => $field->option('attr', []),
        ];
    }
}
