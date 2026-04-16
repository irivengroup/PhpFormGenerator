<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use InvalidArgumentException;
use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormPluginKernel;
use Iriven\PhpFormGenerator\Infrastructure\Registry\InMemoryFieldTypeRegistry;
use Iriven\PhpFormGenerator\Infrastructure\Registry\InMemoryFormTypeRegistry;
use Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\DemoPlugin;
use Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\EmptyPlugin;
use Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\OverridePlugin;
use PHPUnit\Framework\TestCase;

final class PluginRuntimeRegressionTest extends TestCase
{
    public function testEmptyPluginCanBeRegisteredSafely(): void
    {
        $kernel = (new FormPluginKernel())->register(new EmptyPlugin());

        self::assertCount(1, $kernel->plugins()->all());
    }

    public function testPluginRegistrationOrderKeepsLatestAliasWhenOverrideAllowed(): void
    {
        $kernel = (new FormPluginKernel())
            ->register(new DemoPlugin())
            ->register(new OverridePlugin());

        self::assertSame(
            'Iriven\\PhpFormGenerator\\Tests\\Fixtures\\Plugin\\SlugType',
            $kernel->fieldTypes()->resolve('slug')
        );
        self::assertSame(
            'Iriven\\PhpFormGenerator\\Tests\\Fixtures\\Plugin\\NewsletterType',
            $kernel->formTypes()->resolve('newsletter')
        );
    }

    public function testFieldRegistryRejectsOverrideWhenDisabled(): void
    {
        $registry = new InMemoryFieldTypeRegistry([], false);
        $registry->register('slug', 'App\\One');

        $this->expectException(InvalidArgumentException::class);
        $registry->register('slug', 'App\\Two');
    }

    public function testFormRegistryRejectsOverrideWhenDisabled(): void
    {
        $registry = new InMemoryFormTypeRegistry([], false);
        $registry->register('newsletter', 'App\\One');

        $this->expectException(InvalidArgumentException::class);
        $registry->register('newsletter', 'App\\Two');
    }

    public function testFactoryStillWorksAfterMultiplePluginRegistrations(): void
    {
        $kernel = (new FormPluginKernel())
            ->register(new EmptyPlugin())
            ->register(new DemoPlugin())
            ->register(new OverridePlugin());

        $factory = new FormFactory(pluginKernel: $kernel);
        $form = $factory->create('newsletter');

        self::assertArrayHasKey('email', $form->fields());
    }
}
