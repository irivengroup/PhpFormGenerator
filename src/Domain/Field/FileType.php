<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class FileType extends AbstractFieldType
{
    public function name(): string
    {
        return 'file';
    }

    public function configureOptions(array $options = []): array
    {
        return parent::configureOptions(array_merge(['type' => 'file'], $options));
    }
}
