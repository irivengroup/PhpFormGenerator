<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Schema\Migration;

use Iriven\Fluxon\Application\Schema\SchemaMigrationInterface;

/**
 * @api
 */
final class V20ToV21SchemaMigration implements SchemaMigrationInterface
{
    public function fromVersion(): string
    {
        return '2.0';
    }

    public function toVersion(): string
    {
        return '2.1';
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    public function migrate(array $schema): array
    {
        $schema['schema']['migrated_from'] = '2.0';
        $schema['schema']['compatibility'] = 'forward-readable';
        return $schema;
    }
}
