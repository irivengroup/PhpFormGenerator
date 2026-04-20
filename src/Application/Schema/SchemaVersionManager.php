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
        if (array_key_exists('schema', $schema) && is_array($schema['schema'])) {
            $schema['schema'] = ['version' => $this->currentVersion] + $schema['schema'];

            return $schema;
        }

        return $schema;
    }
}
