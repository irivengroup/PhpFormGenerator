<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Tests;
use Iriven\PhpFormGenerator\Application\Rendering\RenderProfile;
use Iriven\PhpFormGenerator\Application\Rendering\RenderProfileManager;
use Iriven\PhpFormGenerator\Application\Rendering\RenderingChannel;
use Iriven\PhpFormGenerator\Application\Rendering\ThemeDefinition;
use Iriven\PhpFormGenerator\Application\Rendering\ThemeRegistry;
use Iriven\PhpFormGenerator\Application\Rendering\ThemeResolver;
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
