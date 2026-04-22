<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Rendering;

/**
 * @api
 */
final class ThemeRegistry
{
    /** @var array<string, ThemeDefinition> */
    private array $themes = [];

    /**
     * @param array<int, ThemeDefinition> $themes
     */
    public function __construct(array $themes = [])
    {
        foreach ($themes as $theme) {
            $this->register($theme);
        }
    }

    public function register(ThemeDefinition $theme): self
    {
        $this->themes[$theme->name()] = $theme;

        return $this;
    }

    public function get(string $name): ?ThemeDefinition
    {
        return $this->themes[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->themes[$name]);
    }

    /**
     * @return array<string, ThemeDefinition>
     */
    public function all(): array
    {
        return $this->themes;
    }

    public function count(): int
    {
        return count($this->themes);
    }
}
