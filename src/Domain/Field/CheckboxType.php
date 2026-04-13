<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class CheckboxType extends AbstractFieldType
{
    public function name(): string
    {
        return 'checkbox';
    }

    public function configureOptions(array $options = []): array
    {
        return parent::configureOptions(array_merge(['type' => 'checkbox'], $options));
    }

    public function normalizeSubmittedValue(mixed $value, array $options = []): mixed
    {
        return $value === '1' || $value === 1 || $value === true || $value === 'on';
    }
}
