<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class HiddenType extends AbstractFieldType
{
    public function name(): string
    {
        return 'hidden';
    }

    public function configureOptions(array $options = []): array
    {
        return parent::configureOptions(array_merge(['type' => 'hidden'], $options));
    }
}
