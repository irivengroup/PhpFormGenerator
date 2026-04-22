<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Transformer;

use Iriven\Fluxon\Domain\Contract\DataTransformerInterface;

final class FloatTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return $value === null || $value === '' ? null : (string) (float) $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        return $value === null || $value === '' ? null : (float) $value;
    }
}
