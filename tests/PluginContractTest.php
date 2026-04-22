<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Domain\Contract\FieldExtensionInterface;
use Iriven\Fluxon\Domain\Contract\PluginInterface;
use PHPUnit\Framework\TestCase;

final class PluginContractTest extends TestCase
{
    public function testPluginContractsExist(): void
    {
        self::assertTrue(interface_exists(PluginInterface::class));
        self::assertTrue(interface_exists(FieldExtensionInterface::class));
    }
}
