<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Registry;

use InvalidArgumentException;
use Iriven\PhpFormGenerator\Domain\Contract\FieldTypeRegistryInterface;

final class InMemoryFieldTypeRegistry implements FieldTypeRegistryInterface
{
    /** @var array<string, string> */
    private array $types = [];

    public function __construct(
        array $initial = [],
        private readonly bool $allowOverride = true,
    ) {
        foreach ($initial as $alias => $typeClass) {
            $this->register((string) $alias, (string) $typeClass);
        }
    }

    public function register(string $alias, string $typeClass): void
    {
        $alias = $this->normalizeAlias($alias);
        $this->assertTypeClass($typeClass);

        if (!$this->allowOverride && isset($this->types[$alias])) {
            throw new InvalidArgumentException(sprintf('Field type alias "%s" is already registered.', $alias));
        }

        $this->types[$alias] = $typeClass;
    }

    public function has(string $alias): bool
    {
        return array_key_exists($this->normalizeAlias($alias), $this->types);
    }

    public function resolve(string $alias): ?string
    {
        return $this->types[$this->normalizeAlias($alias)] ?? null;
    }

    /** @return array<string, string> */
    public function all(): array
    {
        return $this->types;
    }

    private function normalizeAlias(string $alias): string
    {
        $alias = strtolower(trim($alias));
        if ($alias === '') {
            throw new InvalidArgumentException('Field type alias cannot be empty.');
        }

        return $alias;
    }

    private function assertTypeClass(string $typeClass): void
    {
        if (trim($typeClass) === '') {
            throw new InvalidArgumentException('Field type class cannot be empty.');
        }
    }
}
