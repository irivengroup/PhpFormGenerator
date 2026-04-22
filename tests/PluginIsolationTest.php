<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use PHPUnit\Framework\TestCase;
final class PluginIsolationTest extends TestCase
{
    public function testPluginIsolationPlaceholderRemainsTrue(): void
    {
        self::assertTrue(true);
    }
}
