<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Schema\SchemaVersionManager;
use PHPUnit\Framework\TestCase;

final class SchemaVersionManagerRegressionTest extends TestCase
{
    public function testVersionOfFallsBackToV1WhenMissing(): void
    {
        self::assertSame('1.0', (new SchemaVersionManager())->versionOf([]));
    }

    public function testStampPreservesExistingSchemaMetadata(): void
    {
        $schema = (new SchemaVersionManager('2.1'))->stamp(['schema' => ['compatibility' => 'forward-readable']]);

        self::assertSame('2.1', $schema['schema']['version']);
        self::assertSame('forward-readable', $schema['schema']['compatibility']);
    }
}
