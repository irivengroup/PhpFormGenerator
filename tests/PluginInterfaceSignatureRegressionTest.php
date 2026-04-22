<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Domain\Contract\PluginInterface;
use Iriven\Fluxon\Tests\Fixtures\Plugin\DemoPlugin;
use Iriven\Fluxon\Tests\Fixtures\Plugin\EmptyPlugin;
use Iriven\Fluxon\Tests\Fixtures\Plugin\OverridePlugin;
use PHPUnit\Framework\TestCase;

final class PluginInterfaceSignatureRegressionTest extends TestCase
{
    public function testProjectPluginFixturesImplementPluginInterface(): void
    {
        self::assertInstanceOf(PluginInterface::class, new DemoPlugin());
        self::assertInstanceOf(PluginInterface::class, new EmptyPlugin());
        self::assertInstanceOf(PluginInterface::class, new OverridePlugin());
    }
}
