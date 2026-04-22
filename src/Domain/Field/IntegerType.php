<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Field;

use Iriven\Fluxon\Domain\Contract\DataTransformerInterface;
use Iriven\Fluxon\Domain\Transformer\IntegerTransformer;

class IntegerType extends NumberType
{
    /** @return array<int, DataTransformerInterface> */
    public static function defaultTransformers(): array
    {
        return [new IntegerTransformer()];
    }
}
