<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class TextType extends AbstractFieldType
{
    public function name(): string
    {
        return 'text';
    }

    public function configureOptions(array $options = []): array
    {
        return parent::configureOptions(array_merge(['type' => 'text'], $options));
    }
}
