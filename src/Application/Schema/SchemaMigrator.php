<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Schema;

/**
 * @api
 */
final class SchemaMigrator
{
    /** @var list<SchemaMigrationInterface> */
    private array $migrations = [];

    /**
     * @param list<SchemaMigrationInterface> $migrations
     */
    public function __construct(array $migrations = [])
    {
        $this->migrations = $migrations;
    }

    public function register(SchemaMigrationInterface $migration): self
    {
        $this->migrations[] = $migration;

        return $this;
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    public function migrate(array $schema, string $targetVersion): array
    {
        $schema['schema'] = is_array($schema['schema'] ?? null) ? $schema['schema'] : [];
        $current = (string) ($schema['schema']['version'] ?? '1.0');
        $guard = 0;

        if ($current === $targetVersion) {
            return $schema;
        }

        while ($current !== $targetVersion && $guard < 20) {
            $migration = $this->findMigration($current);

            if (!$migration instanceof SchemaMigrationInterface) {
                break;
            }

            $schema = $migration->migrate($schema);
            $schema['schema'] = is_array($schema['schema'] ?? null) ? $schema['schema'] : [];
            $schema['schema']['version'] = $migration->toVersion();
            $current = $migration->toVersion();
            $guard++;
        }

        return $schema;
    }

    public function canMigrate(string $fromVersion): bool
    {
        return $this->findMigration($fromVersion) instanceof SchemaMigrationInterface;
    }

    private function findMigration(string $fromVersion): ?SchemaMigrationInterface
    {
        foreach ($this->migrations as $migration) {
            if ($migration->fromVersion() === $fromVersion) {
                return $migration;
            }
        }

        return null;
    }
}
