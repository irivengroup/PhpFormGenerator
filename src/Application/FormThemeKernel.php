<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application;

use Iriven\Fluxon\Infrastructure\Registry\InMemoryThemeRegistry;
use Iriven\Fluxon\Presentation\Html\Theme\Bootstrap5Theme;
use Iriven\Fluxon\Presentation\Html\Theme\DefaultTheme;
use Iriven\Fluxon\Presentation\Html\Theme\TailwindTheme;
use Iriven\Fluxon\Presentation\Html\Theme\ThemeInterface;

final class FormThemeKernel
{
    private InMemoryThemeRegistry $themes;

    public function __construct()
    {
        $this->themes = new InMemoryThemeRegistry();
        $this->bootDefaults();
    }

    public function register(string $alias, ThemeInterface $theme): self
    {
        $this->themes->register($alias, $theme);

        return $this;
    }

    public function themes(): InMemoryThemeRegistry
    {
        return $this->themes;
    }

    private function bootDefaults(): void
    {
        $this->themes->register('default', new DefaultTheme());
        $this->themes->register('bootstrap5', new Bootstrap5Theme());
        $this->themes->register('tailwind', new TailwindTheme());
    }
}
