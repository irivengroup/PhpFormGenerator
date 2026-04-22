<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\MakeDtoFormCommand;
use Iriven\Fluxon\Application\Cli\DebugDtoGuessCommand;
use PHPUnit\Framework\TestCase;

final class DtoCliCommandTest extends TestCase
{
    public function testMakeDtoFormReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new MakeDtoFormCommand())->run(), true));
    }

    public function testDebugDtoGuessReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new DebugDtoGuessCommand())->run(), true));
    }
}
