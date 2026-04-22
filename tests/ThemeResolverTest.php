<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\Rendering\ThemeDefinition;
use Iriven\Fluxon\Application\Rendering\ThemeRegistry;
use Iriven\Fluxon\Application\Rendering\ThemeResolver;
use PHPUnit\Framework\TestCase;
final class ThemeResolverTest extends TestCase
{
    public function testUnknownThemeFallsBackToDefault(): void
    {
        $resolver = new ThemeResolver(new ThemeRegistry([new ThemeDefinition('default')]));
        self::assertSame('default', $resolver->resolve('unknown'));
    }
    public function testThemeInheritanceMergesComponents(): void
    {
        $resolver = new ThemeResolver(new ThemeRegistry([
            new ThemeDefinition('default', null, ['field' => 'base-field']),
            new ThemeDefinition('tailwind', 'default', ['button' => 'tw-button']),
        ]));
        self::assertSame(['field' => 'base-field', 'button' => 'tw-button'], $resolver->components('tailwind'));
    }
}
