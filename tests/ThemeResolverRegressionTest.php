<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Rendering\ThemeDefinition;
use Iriven\Fluxon\Application\Rendering\ThemeRegistry;
use Iriven\Fluxon\Application\Rendering\ThemeResolver;
use PHPUnit\Framework\TestCase;

final class ThemeResolverRegressionTest extends TestCase
{
    public function testUnknownThemeFallsBackToDefault(): void
    {
        $resolver = new ThemeResolver(new ThemeRegistry([new ThemeDefinition('default')]));
        self::assertSame('default', $resolver->resolve('unknown'));
    }

    public function testMissingParentDoesNotBreakComponentResolution(): void
    {
        $resolver = new ThemeResolver(new ThemeRegistry([
            new ThemeDefinition('default'),
            new ThemeDefinition('child', 'missing-parent', ['field' => 'child-field']),
        ]));

        self::assertSame(['field' => 'child-field'], $resolver->components('child'));
    }

    public function testDeepInheritanceRemainsStable(): void
    {
        $resolver = new ThemeResolver(new ThemeRegistry([
            new ThemeDefinition('default', null, ['field' => 'base']),
            new ThemeDefinition('tailwind', 'default', ['button' => 'tw']),
            new ThemeDefinition('tailwind-dark', 'tailwind', ['panel' => 'dark']),
        ]));

        self::assertSame(
            ['field' => 'base', 'button' => 'tw', 'panel' => 'dark'],
            $resolver->components('tailwind-dark')
        );
    }
}
