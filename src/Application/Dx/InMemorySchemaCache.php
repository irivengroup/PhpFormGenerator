<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Dx;

/** @api */
final class InMemorySchemaCache
{
    /** @var array<string, array<string, mixed>> */
    private array $items = [];

    /**
     * @return array<string, mixed>|null
     */
    public function get(string $key): ?array
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param array<string, mixed> $value
     */
    public function set(string $key, array $value): void
    {
        $this->items[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }
}
