<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSchemaRendererConfig;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FrontendPropsMergeTest extends TestCase
{
    public function testUiPropsOverrideDefaultProps(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType', ['ui_props' => ['size' => 'lg']]);
        $form = $builder->getForm();

        $sdk = new FrontendSdk(
            new FormSchemaManager(new ArraySchemaExporter()),
            rendererConfig: new FrontendSchemaRendererConfig(defaultProps: ['size' => 'md', 'clearable' => false])
        );

        $schema = $sdk->buildSchema($form);

        self::assertSame('lg', $schema['fields'][0]['props']['size']);
        self::assertFalse($schema['fields'][0]['props']['clearable']);
    }

    public function testNonArrayUiPropsAreIgnored(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType', ['ui_props' => 'invalid']);
        $form = $builder->getForm();

        $sdk = new FrontendSdk(
            new FormSchemaManager(new ArraySchemaExporter()),
            rendererConfig: new FrontendSchemaRendererConfig(defaultProps: ['size' => 'md'])
        );

        $schema = $sdk->buildSchema($form);

        self::assertSame('md', $schema['fields'][0]['props']['size']);
    }
}
