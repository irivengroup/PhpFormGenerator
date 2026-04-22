<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Frontend\UiComponentResolver;
use PHPUnit\Framework\TestCase;

final class UiComponentResolverRegressionTest extends TestCase
{
    public function testUnknownFieldTypeFallsBackToTextInput(): void
    {
        $resolver = new UiComponentResolver();

        self::assertSame('input:text', $resolver->resolve('UnknownCustomType'));
    }
}
