<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\DebugCacheCommand;
use Iriven\Fluxon\Application\Cli\DebugEventsCommand;
use Iriven\Fluxon\Application\Cli\DebugFormCommand;
use Iriven\Fluxon\Application\Cli\DebugPipelineCommand;
use PHPUnit\Framework\TestCase;

final class DebugRuntimeCliRegressionTest extends TestCase
{
    public function testAllRuntimeDebugCommandsAlwaysReturnJson(): void
    {
        self::assertNotFalse(json_decode((new DebugFormCommand())->run(), true));
        self::assertNotFalse(json_decode((new DebugPipelineCommand())->run(), true));
        self::assertNotFalse(json_decode((new DebugEventsCommand())->run(), true));
        self::assertNotFalse(json_decode((new DebugCacheCommand())->run(), true));
    }
}
