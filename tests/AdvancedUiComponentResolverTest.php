<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\Frontend\AdvancedUiComponentResolver;
use Iriven\PhpFormGenerator\Application\Frontend\UiComponentMap;
use PHPUnit\Framework\TestCase;

final class AdvancedUiComponentResolverTest extends TestCase
{
    public function testDefaultMappingRemainsAvailable(): void
    {
        $resolver = new AdvancedUiComponentResolver();

        self::assertSame('input:text', $resolver->resolve('TextType'));
    }

    public function testMappingCanBeOverridden(): void
    {
        $resolver = new AdvancedUiComponentResolver(
            componentMap: new UiComponentMap(['TextType' => 'ui.text.custom'])
        );

        self::assertSame('ui.text.custom', $resolver->resolve('TextType'));
    }
}
