<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Plugin\PluginValidator;
use Iriven\Fluxon\Tests\Fixtures\Plugin\DemoPlugin;
use PHPUnit\Framework\TestCase;

final class PluginValidatorTest extends TestCase
{
    public function testProjectPluginFixturePassesValidation(): void
    {
        (new PluginValidator())->validate(new DemoPlugin());
        self::assertTrue(true);
    }
}
