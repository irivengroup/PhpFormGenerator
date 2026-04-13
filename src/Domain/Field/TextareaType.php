<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

class TextareaType extends AbstractFieldType
{
    public function renderType(): string
    {
        return 'textarea';
    }
}
