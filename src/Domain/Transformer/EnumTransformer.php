<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Transformer;

use BackedEnum;
use InvalidArgumentException;
use Iriven\PhpFormGenerator\Domain\Contract\DataTransformerInterface;
use UnitEnum;

final class EnumTransformer implements DataTransformerInterface
{
    /**
     * @param string $enumClass
     */
    public function __construct(private readonly string $enumClass)
    {
    }

    public function transform(mixed $value): mixed
    {
        if ($value instanceof BackedEnum) {
            /** @var object{value:int|string} $value */
            return $value->value;
        }

        if ($value instanceof UnitEnum) {
            /** @var object{name:string} $value */
            return $value->name;
        }

        return $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof UnitEnum) {
            return $value;
        }

        if (!enum_exists($this->enumClass)) {
            throw new InvalidArgumentException('Enum class does not exist: ' . $this->enumClass);
        }

        if (is_subclass_of($this->enumClass, BackedEnum::class)) {
            /** @var string $backedEnumClass */
            $backedEnumClass = $this->enumClass;

            return $backedEnumClass::from($value);
        }

        /** @var string $unitEnumClass */
        $unitEnumClass = $this->enumClass;

        foreach ($unitEnumClass::cases() as $case) {
            /** @var object{name:string} $case */
            if ($case->name === (string) $value) {
                return $case;
            }
        }

        throw new InvalidArgumentException(sprintf(
            'Value "%s" is not a valid case name for enum %s.',
            (string) $value,
            $this->enumClass
        ));
    }
}
