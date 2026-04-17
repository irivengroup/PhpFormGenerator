<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormPluginKernel;
use Iriven\PhpFormGenerator\Infrastructure\Registry\PluginRegistry;
use PHPUnit\Framework\TestCase;

final class PluginKernelAccessorRegressionTest extends TestCase
{
    public function testPluginsAccessorRemainsAvailable(): void
    {
        $kernel = new FormPluginKernel();

        self::assertInstanceOf(PluginRegistry::class, $kernel->plugins());
        self::assertSame($kernel->plugins(), $kernel->registry());
    }
}
