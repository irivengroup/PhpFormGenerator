<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Frontend\FrontendFrameworkPresets;
use PHPUnit\Framework\TestCase;

final class FrontendFrameworkPresetsTest extends TestCase
{
    public function testFrameworkPresetsExposeNamedConfigurations(): void
    {
        self::assertSame('react', FrontendFrameworkPresets::react()->framework());
        self::assertSame('vue', FrontendFrameworkPresets::vue()->framework());
        self::assertSame('mobile', FrontendFrameworkPresets::mobile()->framework());
    }
}
