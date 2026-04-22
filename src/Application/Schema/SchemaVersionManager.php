<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Schema;

/**
 * @api
 */
final class SchemaVersionManager
{
    public function __construct(private readonly string $currentVersion = '2.1')
    {
    }

    public function currentVersion(): string
    {
        return $this->currentVersion;
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    public function stamp(array $schema): array
    {
        $existing = is_array($schema['schema'] ?? null) ? $schema['schema'] : [];
        $schema['schema'] = ['version' => $this->currentVersion] + $existing;

        return $schema;
    }

    /**
     * @param array<string, mixed> $schema
     */
    public function versionOf(array $schema): string
    {
        return (string) ($schema['schema']['version'] ?? '1.0');
    }
}
