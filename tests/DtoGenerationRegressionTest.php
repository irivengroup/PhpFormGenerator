<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\Generation\DtoFormGuesser;
use Iriven\PhpFormGenerator\Application\Generation\ExampleSchemaFormGenerator;
use PHPUnit\Framework\TestCase;

final class DtoGenerationRegressionTest extends TestCase
{
    public function testEmptyArrayProducesEmptyGuess(): void
    {
        self::assertSame([], (new DtoFormGuesser())->guess([]));
    }

    public function testNullFallsBackToTextType(): void
    {
        $guess = (new DtoFormGuesser())->guess(['nickname' => null]);

        self::assertSame('TextType', $guess['nickname']);
    }

    public function testSchemaGenerationIsDeterministic(): void
    {
        $schema = (new ExampleSchemaFormGenerator())->generate([
            'b' => true,
            'a' => 'x',
        ]);

        self::assertSame(['a', 'b'], array_keys($schema['fields']));
    }
}
