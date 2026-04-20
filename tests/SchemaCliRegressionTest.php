<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\Cli\MigrateSchemaCommand;
use PHPUnit\Framework\TestCase;

final class SchemaCliRegressionTest extends TestCase
{
    public function testMigrateSchemaCommandAcceptsExplicitVersions(): void
    {
        $json = (new MigrateSchemaCommand())->run(['2.0', '2.1']);
        $data = json_decode($json, true);

        self::assertSame('2.1', $data['schema']['version']);
    }
}
