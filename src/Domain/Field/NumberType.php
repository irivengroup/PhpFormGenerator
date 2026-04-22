<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Field;

class NumberType extends TextType
{
    public static function htmlType(): string { return 'number'; }
}
