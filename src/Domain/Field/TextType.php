<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

use Iriven\PhpFormGenerator\Domain\Contract\FieldTypeInterface;

final class TextType implements FieldTypeInterface
{
    public function renderType(): string
    {
        return 'text';
    }
}
