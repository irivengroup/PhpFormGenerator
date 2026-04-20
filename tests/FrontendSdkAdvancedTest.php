<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormRuntimeContext;
use Iriven\PhpFormGenerator\Application\FormSchemaManager;
use Iriven\PhpFormGenerator\Application\Frontend\FrontendSchemaRendererConfig;
use Iriven\PhpFormGenerator\Application\Frontend\FrontendSdk;
use Iriven\PhpFormGenerator\Application\Frontend\UiComponentMap;
use Iriven\PhpFormGenerator\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FrontendSdkAdvancedTest extends TestCase
{
    public function testAdvancedSchemaContainsUiComponentPropsAndHints(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType', [
            'label' => 'Name',
            'placeholder' => 'Your name',
            'help' => 'Enter full name',
            'ui_props' => ['clearable' => true],
        ]);
        $form = $builder->getForm();

        $sdk = new FrontendSdk(
            new FormSchemaManager(new ArraySchemaExporter()),
            rendererConfig: new FrontendSchemaRendererConfig(new UiComponentMap(), ['size' => 'md'])
        );

        $schema = $sdk->buildSchema($form, new FormRuntimeContext($form, 'tailwind', 'RendererClass', ['variant' => 'compact']));

        self::assertSame('input:text', $schema['fields'][0]['component']);
        self::assertSame('md', $schema['fields'][0]['props']['size']);
        self::assertTrue($schema['fields'][0]['props']['clearable']);
        self::assertSame('Your name', $schema['fields'][0]['ui_hints']['placeholder']);
    }

    public function testAdvancedSchemaSupportsComponentOverride(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');
        $form = $builder->getForm();

        $sdk = new FrontendSdk(
            new FormSchemaManager(new ArraySchemaExporter()),
            rendererConfig: new FrontendSchemaRendererConfig(
                new UiComponentMap(['TextType' => 'ui.text.custom'])
            )
        );

        $schema = $sdk->buildSchema($form);

        self::assertSame('ui.text.custom', $schema['fields'][0]['component']);
        self::assertSame('ui.text.custom', $schema['ui']['component_overrides']['TextType']);
    }
}
