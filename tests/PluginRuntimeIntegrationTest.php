<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormPluginKernel;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\DemoPlugin;
use PHPUnit\Framework\TestCase;

final class PluginRuntimeIntegrationTest extends TestCase
{
    public function testFactoryCanResolvePluginFormTypeAlias(): void
    {
        $kernel = (new FormPluginKernel())->register(new DemoPlugin());
        $factory = new FormFactory(pluginKernel: $kernel);

        $form = $factory->create('newsletter');

        self::assertSame('form', $form->getName());
        self::assertArrayHasKey('email', $form->fields());
    }

    public function testBuilderCanResolvePluginFieldTypeAlias(): void
    {
        $kernel = (new FormPluginKernel())->register(new DemoPlugin());
        $factory = new FormFactory(pluginKernel: $kernel);
        $builder = $factory->createBuilder('demo');

        $builder->add('slug', 'slug', ['required' => true]);
        $form = $builder->getForm();

        self::assertArrayHasKey('slug', $form->fields());
        self::assertSame('Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\SlugType', $form->fields()['slug']->typeClass);
    }

    public function testPluginFieldExtensionIsAppliedAtRuntime(): void
    {
        $kernel = (new FormPluginKernel())->register(new DemoPlugin());
        $factory = new FormFactory(pluginKernel: $kernel);
        $builder = $factory->createBuilder('demo');

        $builder->add('slug', 'slug');
        $form = $builder->getForm();

        $form->handleRequest(new ArrayRequest('POST', [
            'demo' => ['slug' => '  my-post  '],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertSame('my-post', $form->getData()['slug']);
    }
}
