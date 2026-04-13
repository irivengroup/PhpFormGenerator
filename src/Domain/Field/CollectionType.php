<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class CollectionType extends AbstractFieldType
{
    public function renderType(): string
    {
        return 'collection';
    }

    public function isCompound(): bool
    {
        return true;
    }

    public function isCollection(): bool
    {
        return true;
    }

    public function normalizeOptions(array $options): array
    {
        $options = parent::normalizeOptions($options);
        $options['entry_type'] ??= TextType::class;
        $options['entry_options'] ??= [];
        $options['prototype'] ??= true;
        $options['allow_add'] ??= true;
        $options['allow_delete'] ??= true;
        $options['show_label'] ??= true;

        return $options;
    }
}
