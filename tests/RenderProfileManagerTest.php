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
final class RenderProfileManagerTest extends TestCase
{
    public function testRenderProfileExportsThemeAndChannel(): void
    {
        $manager = new RenderProfileManager(new ThemeResolver(new ThemeRegistry([new ThemeDefinition('default')])));
        $data = $manager->export(new RenderProfile('default', RenderingChannel::EMAIL, ['mode' => 'compact']));
        self::assertSame('default', $data['theme']);
        self::assertSame('email', $data['channel']);
        self::assertSame('compact', $data['metadata']['mode']);
    }
}
