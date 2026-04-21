<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\Cli\MakeDtoFormCommand;
use Iriven\PhpFormGenerator\Application\Cli\DebugDtoGuessCommand;
use PHPUnit\Framework\TestCase;

final class DtoCliRegressionTest extends TestCase
{
    public function testMakeDtoFormAlwaysReturnsValidJson(): void
    {
        self::assertNotFalse(json_decode((new MakeDtoFormCommand())->run(), true));
    }

    public function testDebugDtoGuessAlwaysReturnsValidJson(): void
    {
        self::assertNotFalse(json_decode((new DebugDtoGuessCommand())->run(), true));
    }
}
