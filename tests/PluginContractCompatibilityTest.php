<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Infrastructure\Extension\ExtensionRegistry;
use Iriven\Fluxon\Infrastructure\Registry\InMemoryFieldTypeRegistry;
use Iriven\Fluxon\Infrastructure\Registry\InMemoryFormTypeRegistry;
use Iriven\Fluxon\Infrastructure\Registry\PluginRegistry;
use Iriven\Fluxon\Tests\Fixtures\Plugin\DemoPlugin;
use PHPUnit\Framework\TestCase;

final class PluginContractCompatibilityTest extends TestCase
{
    public function testLegacyPluginContractRemainsCompatibleWithRegistry(): void
    {
        $registry = new PluginRegistry(
            new InMemoryFieldTypeRegistry(),
            new InMemoryFormTypeRegistry(),
            new ExtensionRegistry(),
        );

        $registry->registerPlugin(new DemoPlugin());

        self::assertCount(1, $registry->all());
    }
}
