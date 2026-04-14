<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

class TextareaType extends AbstractFieldType
{
    public static function htmlType(): string
    {
        return 'textarea';
    }
}
