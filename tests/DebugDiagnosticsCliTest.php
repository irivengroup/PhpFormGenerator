<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\DebugDiagnosticsCommand;
use Iriven\Fluxon\Application\Cli\DebugHealthCommand;
use Iriven\Fluxon\Application\Cli\DebugProfileCommand;
use PHPUnit\Framework\TestCase;

final class DebugDiagnosticsCliTest extends TestCase
{
    public function testProductionDebugCommandsReturnJson(): void
    {
        self::assertNotFalse(json_decode((new DebugDiagnosticsCommand())->run(), true));
        self::assertNotFalse(json_decode((new DebugProfileCommand())->run(), true));
        self::assertNotFalse(json_decode((new DebugHealthCommand())->run(), true));
    }
}
