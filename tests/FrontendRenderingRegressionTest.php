<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FrontendRenderingRegressionTest extends TestCase
{
    public function testRuntimeRenderingNodeIsAlwaysPresent(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter())))->buildSchema($form);

        self::assertArrayHasKey('rendering', $schema['runtime']);
        self::assertArrayHasKey('theme', $schema['runtime']['rendering']);
        self::assertArrayHasKey('channel', $schema['runtime']['rendering']);
        self::assertArrayHasKey('metadata', $schema['runtime']['rendering']);
    }

    public function testRuntimeRenderingMetadataIsAlwaysArray(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter())))->buildSchema($form);

        self::assertIsArray($schema['runtime']['rendering']['metadata']);
    }

    public function testRuntimeThemeAndChannelStayCoherentWithRuntimeContext(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter())))->buildSchema(
            $form,
            new FormRuntimeContext($form, 'tailwind', 'RendererClass', ['channel' => 'email'])
        );

        self::assertSame('tailwind', $schema['runtime']['rendering']['theme']);
        self::assertSame('email', $schema['runtime']['rendering']['channel']);
        self::assertSame('tailwind', $schema['ui']['theme']);
    }
}
