<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\PropertyAccess;

final class PropertyReader
{
    public function getValue(mixed $source, string $path, mixed $default = null): mixed
    {
        if ($path === '') {
            return $source;
        }

        $current = $source;
        foreach ($this->segments($path) as $segment) {
            if (!$this->canReadSegment($current, $segment)) {
                return $default;
            }

            $current = $this->readSegment($current, $segment, $default);
        }

        return $current;
    }

    /** @return array<int,string> */
    private function segments(string $path): array
    {
        return explode('.', $path);
    }

    private function canReadSegment(mixed $current, string $segment): bool
    {
        if (is_array($current)) {
            return array_key_exists($segment, $current);
        }

        if (!is_object($current)) {
            return false;
        }

        return $this->hasObjectSegment($current, $segment);
    }

    private function readSegment(mixed $current, string $segment, mixed $default): mixed
    {
        if (is_array($current)) {
            return $current[$segment] ?? $default;
        }

        if (!is_object($current)) {
            return $default;
        }

        return $this->readObjectSegment($current, $segment, $default);
    }

    private function hasObjectSegment(object $current, string $segment): bool
    {
        return method_exists($current, $this->getterName($segment))
            || method_exists($current, $this->isserName($segment))
            || property_exists($current, $segment);
    }

    private function readObjectSegment(object $current, string $segment, mixed $default): mixed
    {
        $getter = $this->getterName($segment);
        $isser = $this->isserName($segment);

        if (method_exists($current, $getter)) {
            return $current->{$getter}();
        }

        if (method_exists($current, $isser)) {
            return $current->{$isser}();
        }

        if (property_exists($current, $segment)) {
            return $current->{$segment};
        }

        return $default;
    }

    private function getterName(string $segment): string
    {
        return 'get' . ucfirst($segment);
    }

    private function isserName(string $segment): string
    {
        return 'is' . ucfirst($segment);
    }
}
