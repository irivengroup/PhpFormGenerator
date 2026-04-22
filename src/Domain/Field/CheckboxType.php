<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Field;

use Iriven\Fluxon\Domain\Contract\DataTransformerInterface;
use Iriven\Fluxon\Domain\Transformer\BooleanTransformer;

class CheckboxType extends AbstractFieldType
{
    public static function htmlType(): string
    {
        return 'checkbox';
    }

    /** @return array<int, DataTransformerInterface> */
    public static function defaultTransformers(): array
    {
        return [new BooleanTransformer()];
    }
}
