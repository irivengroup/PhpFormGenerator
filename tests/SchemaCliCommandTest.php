<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\DebugSchemaVersionCommand;
use Iriven\Fluxon\Application\Cli\MigrateSchemaCommand;
use PHPUnit\Framework\TestCase;

final class SchemaCliCommandTest extends TestCase
{
    public function testDebugSchemaVersionReturnsCurrentVersion(): void
    {
        self::assertSame('2.1', (new DebugSchemaVersionCommand())->run());
    }

    public function testMigrateSchemaReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new MigrateSchemaCommand())->run(), true));
    }
}
