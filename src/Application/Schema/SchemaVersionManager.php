<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Schema;

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
        $schema['schema'] = [
            'version' => $this->currentVersion,
        ] + (is_array($schema['schema'] ?? null) ? $schema['schema'] : []);

        return $schema;
    }
}
