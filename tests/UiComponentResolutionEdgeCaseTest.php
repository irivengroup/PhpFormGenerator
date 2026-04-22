<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Frontend\AdvancedUiComponentResolver;
use Iriven\Fluxon\Application\Frontend\UiComponentMap;
use Iriven\Fluxon\Application\Frontend\UiComponentResolver;
use PHPUnit\Framework\TestCase;

final class UiComponentResolutionEdgeCaseTest extends TestCase
{
    public function testEmptyOverrideFallsBackToSafeDefault(): void
    {
        $resolver = new AdvancedUiComponentResolver(
            new UiComponentResolver(),
            new UiComponentMap(['TextType' => ''])
        );

        self::assertSame('input:text', $resolver->resolve('TextType'));
    }

    public function testFullyQualifiedAndShortNameOverridesAreSupported(): void
    {
        $resolver = new AdvancedUiComponentResolver(
            new UiComponentResolver(),
            new UiComponentMap(['TextType' => 'ui.text.short'])
        );

        self::assertSame('ui.text.short', $resolver->resolve('Iriven\Fluxon\Domain\Field\TextType'));
    }
}
