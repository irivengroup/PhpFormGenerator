<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Tests;
use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormRuntimeContext;
use Iriven\PhpFormGenerator\Application\FormSchemaManager;
use Iriven\PhpFormGenerator\Application\Frontend\FrontendSdk;
use Iriven\PhpFormGenerator\Application\Rendering\RenderProfileManager;
use Iriven\PhpFormGenerator\Application\Rendering\ThemeDefinition;
use Iriven\PhpFormGenerator\Application\Rendering\ThemeRegistry;
use Iriven\PhpFormGenerator\Application\Rendering\ThemeResolver;
use Iriven\PhpFormGenerator\Infrastructure\Schema\ArraySchemaExporter;
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
        self::assertSame('tailwind', $schema['rendering']['theme']);
        self::assertSame('headless', $schema['rendering']['channel']);
        self::assertArrayHasKey('theme_components', $schema['rendering']);
    }
}
