<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Frontend;

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
        if (isset($this->overrides[$fieldType]) && $this->overrides[$fieldType] !== '') {
            return $this->overrides[$fieldType];
        }

        $shortName = $this->shortName($fieldType);

        if (isset($this->overrides[$shortName]) && $this->overrides[$shortName] !== '') {
            return $this->overrides[$shortName];
        }

        return $default !== '' ? $default : 'input:text';
    }

    private function shortName(string $fieldType): string
    {
        $parts = explode('\\', $fieldType);

        return $parts[array_key_last($parts)] ?? $fieldType;
    }
}
