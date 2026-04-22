<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Extension;

use Iriven\Fluxon\Domain\Contract\ConstraintInterface;
use Iriven\Fluxon\Domain\Contract\DataTransformerInterface;
use Iriven\Fluxon\Domain\Contract\FieldTypeExtensionInterface;
use Iriven\Fluxon\Domain\Field\TextType;
use Iriven\Fluxon\Domain\Transformer\StringTrimTransformer;

final class TrimTextFieldExtension implements FieldTypeExtensionInterface
{
    public static function getExtendedType(): string
    {
        return TextType::class;
    }

    public function extendOptions(array $options): array
    {
        return $options;
    }

    public function extendConstraints(array $constraints, array $options): array
    {
        return $constraints;
    }

    public function extendTransformers(array $transformers, array $options): array
    {
        array_unshift($transformers, new StringTrimTransformer());

        return $transformers;
    }
}
