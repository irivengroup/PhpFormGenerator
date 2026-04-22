<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\DebugIntegrationCommand;
use PHPUnit\Framework\TestCase;

final class DebugIntegrationCommandTest extends TestCase
{
    public function testDebugIntegrationReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new DebugIntegrationCommand())->run(), true));
    }
}
