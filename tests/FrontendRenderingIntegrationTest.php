<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Application\Rendering\RenderProfileManager;
use Iriven\Fluxon\Application\Rendering\ThemeDefinition;
use Iriven\Fluxon\Application\Rendering\ThemeRegistry;
use Iriven\Fluxon\Application\Rendering\ThemeResolver;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;
final class FrontendRenderingIntegrationTest extends TestCase
{
    public function testFrontendSchemaContainsRenderingMetadata(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(
            new FormSchemaManager(new ArraySchemaExporter()),
            renderProfileManager: new RenderProfileManager(
                new ThemeResolver(new ThemeRegistry([new ThemeDefinition('default'), new ThemeDefinition('tailwind', 'default')]))
            )
        );
        $schema = $sdk->buildSchema($form, new FormRuntimeContext($form, 'tailwind', 'RendererClass', ['channel' => 'headless']));
        self::assertSame('tailwind', $schema['runtime']['rendering']['theme']);
        self::assertSame('headless', $schema['runtime']['rendering']['channel']);
        self::assertArrayHasKey('theme_components', $schema['runtime']['rendering']);
    }
}
