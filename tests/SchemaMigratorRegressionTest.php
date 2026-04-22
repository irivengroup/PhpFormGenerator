<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Schema\Migration\V20ToV21SchemaMigration;
use Iriven\Fluxon\Application\Schema\SchemaMigrator;
use PHPUnit\Framework\TestCase;

final class SchemaMigratorRegressionTest extends TestCase
{
    public function testMigrateReturnsSameSchemaWhenAlreadyAtTargetVersion(): void
    {
        $schema = ['schema' => ['version' => '2.1']];
        $migrated = (new SchemaMigrator([new V20ToV21SchemaMigration()]))->migrate($schema, '2.1');

        self::assertSame('2.1', $migrated['schema']['version']);
        self::assertArrayNotHasKey('migrated_from', $migrated['schema']);
    }

    public function testMigrateLeavesSchemaReadableWhenNoMigrationExists(): void
    {
        $schema = ['schema' => ['version' => '1.5']];
        $migrated = (new SchemaMigrator([new V20ToV21SchemaMigration()]))->migrate($schema, '2.1');

        self::assertSame('1.5', $migrated['schema']['version']);
    }
}
