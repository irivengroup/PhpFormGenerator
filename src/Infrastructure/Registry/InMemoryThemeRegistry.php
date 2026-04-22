<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Registry;

use InvalidArgumentException;
use Iriven\Fluxon\Domain\Contract\ThemeRegistryInterface;
use Iriven\Fluxon\Presentation\Html\Theme\ThemeInterface;

final class InMemoryThemeRegistry implements ThemeRegistryInterface
{
    /** @var array<string, ThemeInterface> */
    private array $themes = [];

    public function register(string $alias, ThemeInterface $theme): void
    {
        $alias = strtolower(trim($alias));

        if ($alias === '') {
            throw new InvalidArgumentException('Theme alias cannot be empty.');
        }

        $this->themes[$alias] = $theme;
    }

    public function has(string $alias): bool
    {
        return array_key_exists(strtolower(trim($alias)), $this->themes);
    }

    public function resolve(string $alias): ?ThemeInterface
    {
        return $this->themes[strtolower(trim($alias))] ?? null;
    }

    /**
     * @return array<string, ThemeInterface>
     */
    public function all(): array
    {
        return $this->themes;
    }
}
