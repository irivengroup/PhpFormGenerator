<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Field;

class SelectType extends AbstractFieldType
{
    public static function htmlType(): string
    {
        return 'select';
    }
}
