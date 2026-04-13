<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class FormType extends AbstractFieldType
{
    public function renderType(): string
    {
        return 'form';
    }

    public function isCompound(): bool
    {
        return true;
    }

    public function normalizeOptions(array $options): array
    {
        $options = parent::normalizeOptions($options);
        $options['form_type'] ??= null;
        $options['show_label'] ??= true;

        return $options;
    }
}
