<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Constraint\Range;
use Iriven\PhpFormGenerator\Infrastructure\Translation\ArrayTranslator;
use PHPUnit\Framework\TestCase;

final class ConstraintTranslationTest extends TestCase
{
    public function testRequiredConstraintUsesTranslatorWhenProvided(): void
    {
        $translator = new ArrayTranslator([
            'required.invalid' => 'Champ obligatoire',
        ]);

        $constraint = new Required();

        self::assertSame(
            ['Champ obligatoire'],
            $constraint->validate('', ['translator' => $translator])
        );
    }

    public function testRangeConstraintUsesParameterizedTranslation(): void
    {
        $translator = new ArrayTranslator([
            'range.invalid' => 'Valeur attendue entre {{min}} et {{max}}',
        ]);

        $constraint = new Range(3, 7);

        self::assertSame(
            ['Valeur attendue entre 3 et 7'],
            $constraint->validate(10, ['translator' => $translator])
        );
    }
}
