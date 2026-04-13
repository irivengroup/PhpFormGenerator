<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class TextareaType extends AbstractFieldType
{
    public function name(): string
    {
        return 'textarea';
    }

    public function configureOptions(array $options = []): array
    {
        return parent::configureOptions(array_merge(['type' => 'textarea'], $options));
    }
}
