<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Field;

final class YesNoType extends ChoiceType
{
    /**
     * @return array<string, string>
     */
    public static function choices(): array
    {
        return [
            'yes' => 'Yes',
            'no' => 'No',
        ];
    }
}
