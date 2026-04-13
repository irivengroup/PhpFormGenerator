<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class EmailType extends AbstractFieldType
{
    public function name(): string
    {
        return 'email';
    }

    public function configureOptions(array $options = []): array
    {
        return parent::configureOptions(array_merge(['type' => 'email'], $options));
    }
}
