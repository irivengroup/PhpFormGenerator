<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Mapping;

final class ArrayDataMapper
{
    /**
     * @param array<string, mixed> $target
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    public function map(array $target, array $values): array
    {
        return $this->merge($target, $values);
    }

    /**
     * @param array<string, mixed> $target
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    public function merge(array $target, array $values): array
    {
        foreach ($values as $key => $value) {
            if (is_array($value) && isset($target[$key]) && is_array($target[$key])) {
                /** @var array<string, mixed> $existing */
                $existing = $target[$key];
                /** @var array<string, mixed> $nested */
                $nested = $value;
                $target[$key] = $this->merge($existing, $nested);
                continue;
            }

            $target[$key] = $value;
        }

        return $target;
    }
}
