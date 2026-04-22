<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\Cli\ServeCommand;
use PHPUnit\Framework\TestCase;

final class ServeCommandTest extends TestCase
{
    public function testServeCommandReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new ServeCommand())->run(['9090']), true));
    }
}
