<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Domain\Transformer\EnumTransformer;
use PHPUnit\Framework\TestCase;

enum DummyStatus
{
    case Draft;
    case Published;
}

enum DummyFlag: string
{
    case Yes = 'yes';
    case No = 'no';
}

final class EnumTransformerTest extends TestCase
{
    public function testReverseTransformSupportsUnitEnumByCaseName(): void
    {
        $transformer = new EnumTransformer(DummyStatus::class);

        self::assertSame(DummyStatus::Draft, $transformer->reverseTransform('Draft'));
        self::assertSame('Published', $transformer->transform(DummyStatus::Published));
    }

    public function testReverseTransformSupportsBackedEnumByValue(): void
    {
        $transformer = new EnumTransformer(DummyFlag::class);

        self::assertSame(DummyFlag::Yes, $transformer->reverseTransform('yes'));
        self::assertSame('no', $transformer->transform(DummyFlag::No));
    }
}
