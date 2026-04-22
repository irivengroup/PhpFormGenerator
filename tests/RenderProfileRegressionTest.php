<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Rendering\RenderProfile;
use Iriven\Fluxon\Application\Rendering\RenderProfileManager;
use Iriven\Fluxon\Application\Rendering\RenderingChannel;
use Iriven\Fluxon\Application\Rendering\ThemeDefinition;
use Iriven\Fluxon\Application\Rendering\ThemeRegistry;
use Iriven\Fluxon\Application\Rendering\ThemeResolver;
use PHPUnit\Framework\TestCase;

final class RenderProfileRegressionTest extends TestCase
{
    public function testEmptyThemeFallsBackToDefault(): void
    {
        self::assertSame('default', (new RenderProfile(''))->theme());
    }

    public function testEmptyChannelFallsBackToHtml(): void
    {
        self::assertSame(RenderingChannel::HTML, (new RenderProfile('default', ''))->channel());
    }

    public function testEmptyMetadataRemainsArray(): void
    {
        $manager = new RenderProfileManager(new ThemeResolver(new ThemeRegistry([new ThemeDefinition('default')])));
        $data = $manager->export(new RenderProfile('default', RenderingChannel::HEADLESS, []));

        self::assertIsArray($data['metadata']);
        self::assertSame([], $data['metadata']);
    }
}
