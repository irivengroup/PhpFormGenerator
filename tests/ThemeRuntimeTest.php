<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormThemeKernel;
use Iriven\Fluxon\Presentation\Html\HtmlRenderer;
use Iriven\Fluxon\Presentation\Html\HtmlRendererFactory;
use PHPUnit\Framework\TestCase;

final class ThemeRuntimeTest extends TestCase
{
    public function testRendererFactoryCanResolveTailwindTheme(): void
    {
        $kernel = new FormThemeKernel();
        $factory = new HtmlRendererFactory($kernel);
        $renderer = $factory->create('tailwind');

        self::assertInstanceOf(HtmlRenderer::class, $renderer);
    }

    public function testRendererFactoryFallsBackToDefaultTheme(): void
    {
        $kernel = new FormThemeKernel();
        $factory = new HtmlRendererFactory($kernel);
        $renderer = $factory->create('unknown-theme');

        self::assertInstanceOf(HtmlRenderer::class, $renderer);
    }
}
