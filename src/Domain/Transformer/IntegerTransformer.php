<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Transformer;

use Iriven\Fluxon\Domain\Contract\DataTransformerInterface;

final class IntegerTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return $value === null || $value === '' ? null : (string) (int) $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        return $value === null || $value === '' ? null : (int) $value;
    }
}
