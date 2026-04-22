<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormThemeKernel;
use Iriven\Fluxon\Presentation\Html\HtmlRenderer;
use Iriven\Fluxon\Presentation\Html\HtmlRendererFactory;
use Iriven\Fluxon\Tests\Fixtures\Theme\MinimalTheme;
use PHPUnit\Framework\TestCase;

final class ThemeCustomRuntimeTest extends TestCase
{
    public function testCustomThemeAliasCanBeResolved(): void
    {
        $kernel = (new FormThemeKernel())->register('minimal', new MinimalTheme());
        $renderer = (new HtmlRendererFactory($kernel))->create('minimal');

        self::assertInstanceOf(HtmlRenderer::class, $renderer);
    }

    public function testUnknownThemeStillFallsBackToDefaultRenderer(): void
    {
        $kernel = new FormThemeKernel();
        $renderer = (new HtmlRendererFactory($kernel))->create('missing');

        self::assertInstanceOf(HtmlRenderer::class, $renderer);
    }
}
