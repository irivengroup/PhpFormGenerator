<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

interface FieldTypeRegistryInterface
{
    public function register(string $alias, string $typeClass): void;

    public function has(string $alias): bool;

    public function resolve(string $alias): ?string;

    /**
     * @return array<string, string>
     */
    public function all(): array;
}
