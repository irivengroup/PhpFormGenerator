<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Cli;

use Iriven\PhpFormGenerator\Application\Schema\Migration\V20ToV21SchemaMigration;
use Iriven\PhpFormGenerator\Application\Schema\SchemaMigrator;

/**
 * @api
 */
final class MigrateSchemaCommand implements CliCommandInterface
{
    public function name(): string
    {
        return 'migrate:schema';
    }

    /**
     * @param array<int, string> $args
     */
    public function run(array $args = []): string
    {
        $from = trim($args[0] ?? '2.0');
        $to = trim($args[1] ?? '2.1');

        $schema = ['schema' => ['version' => $from]];
        $migrator = new SchemaMigrator([new V20ToV21SchemaMigration()]);
        $migrated = $migrator->migrate($schema, $to);

        return json_encode($migrated, JSON_PRETTY_PRINT) ?: '{}';
    }
}
