<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class SubmitType extends AbstractFieldType
{
    public function name(): string
    {
        return 'submit';
    }

    public function configureOptions(array $options = []): array
    {
        return parent::configureOptions(array_merge(['type' => 'submit'], $options));
    }
}
