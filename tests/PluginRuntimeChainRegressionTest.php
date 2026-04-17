<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormPluginKernel;
use Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\DemoPlugin;
use Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\EmptyPlugin;
use Iriven\PhpFormGenerator\Tests\Fixtures\Plugin\OverridePlugin;
use PHPUnit\Framework\TestCase;

final class PluginRuntimeChainRegressionTest extends TestCase
{
    public function testMultipleProjectPluginFixturesCanStillBeRegistered(): void
    {
        $kernel = new FormPluginKernel();
        $kernel->register(new DemoPlugin());
        $kernel->register(new EmptyPlugin());
        $kernel->register(new OverridePlugin());

        self::assertCount(3, $kernel->plugins()->all());
    }

    public function testFactoryStillBuildsAfterPluginRegistrations(): void
    {
        $kernel = new FormPluginKernel();
        $kernel->register(new EmptyPlugin());

        $factory = new FormFactory(pluginKernel: $kernel);
        $form = $factory->createBuilder('contact')->getForm();

        self::assertSame('contact', $form->getName());
    }
}
