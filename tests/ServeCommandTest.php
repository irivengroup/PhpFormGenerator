<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\ServeCommand;
use PHPUnit\Framework\TestCase;

final class ServeCommandTest extends TestCase
{
    public function testServeCommandReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new ServeCommand())->run(['9090']), true));
    }
}
