<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

class ChoiceType extends SelectType
{
    /**
     * @return array<string, string>
     */
    public static function choices(): array
    {
        return [];
    }
}
