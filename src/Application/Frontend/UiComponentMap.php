<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Frontend;

/**
 * @api
 */
final class UiComponentMap
{
    /**
     * @param array<string, string> $overrides
     */
    public function __construct(private readonly array $overrides = [])
    {
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->overrides;
    }

    public function resolve(string $fieldType, string $default): string
    {
        return $this->overrides[$fieldType] ?? $default;
    }
}
