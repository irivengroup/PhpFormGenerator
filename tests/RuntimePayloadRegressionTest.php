<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Runtime\RuntimePayload;
use PHPUnit\Framework\TestCase;

final class RuntimePayloadRegressionTest extends TestCase
{
    public function testMetadataValueReturnsDefaultWhenMissing(): void
    {
        $payload = new RuntimePayload('tailwind', 'RendererClass', ['variant' => 'compact']);
        self::assertSame('fallback', $payload->metadataValue('missing', 'fallback'));
    }

    public function testWithMetadataReturnsNewPayload(): void
    {
        $payload = new RuntimePayload('tailwind', 'RendererClass', ['variant' => 'compact']);
        $next = $payload->withMetadata(['variant' => 'expanded']);

        self::assertSame('compact', $payload->metadataValue('variant'));
        self::assertSame('expanded', $next->metadataValue('variant'));
    }
}
