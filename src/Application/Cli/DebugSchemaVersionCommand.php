<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Cli;

use Iriven\Fluxon\Application\Schema\SchemaVersionManager;

/**
 * @api
 */
final class DebugSchemaVersionCommand implements CliCommandInterface
{
    public function __construct(private readonly SchemaVersionManager $versionManager = new SchemaVersionManager())
    {
    }

    public function name(): string
    {
        return 'debug:schema-version';
    }

    /**
     * @param array<int, string> $args
     */
    public function run(array $args = []): string
    {
        return $this->versionManager->currentVersion();
    }
}
