<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormRenderManager;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormRuntimePipeline;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\FormThemeKernel;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use Iriven\Fluxon\Presentation\Html\HtmlRendererFactory;
use PHPUnit\Framework\TestCase;

final class RuntimeStaticShapeTest extends TestCase
{
    public function testRenderManagerAcceptsStructuredMetadata(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');

        $manager = new FormRenderManager(new HtmlRendererFactory(new FormThemeKernel()));
        $html = $manager->render($builder->getForm(), 'default', ['variant' => 'compact', 'debug' => true]);

        self::assertIsString($html);
        self::assertStringContainsString('<form', $html);
    }

    public function testRuntimeContextMetadataRoundTrip(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $context = new FormRuntimeContext($form, 'tailwind', 'RendererClass', ['variant' => 'compact']);

        self::assertSame(['variant' => 'compact'], $context->metadata());
    }

    public function testSchemaManagerExportsRuntimeMetadataShape(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');
        $form = $builder->getForm();

        $context = new FormRuntimeContext($form, 'default', 'RendererClass', ['variant' => 'compact']);
        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($form, $context);

        self::assertArrayHasKey('runtime', $schema);
        self::assertIsArray($schema['runtime']);
        self::assertSame('default', $schema['runtime']['theme']);
    }

    public function testRuntimePipelineStagesRemainStable(): void
    {
        self::assertSame(
            ['before_build','after_build','before_submit','after_submit','before_render','after_render','before_export','after_export'],
            (new FormRuntimePipeline())->stages()
        );
    }
}
