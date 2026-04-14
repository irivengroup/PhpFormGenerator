<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Transformer;

use BackedEnum;
use InvalidArgumentException;
use Iriven\PhpFormGenerator\Domain\Contract\DataTransformerInterface;
use ReflectionEnum;
use ReflectionEnumBackedCase;
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
        if (!$value instanceof UnitEnum) {
            return $value;
        }

        $reflection = new ReflectionEnum($value::class);

        foreach ($reflection->getCases() as $caseReflection) {
            if ($caseReflection->getValue() !== $value) {
                continue;
            }

            if ($caseReflection instanceof ReflectionEnumBackedCase) {
                return $caseReflection->getBackingValue();
            }

            return $caseReflection->getName();
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
            /** @var class-string<BackedEnum> $backedEnumClass */
            $backedEnumClass = $this->enumClass;

            return $backedEnumClass::from($value);
        }

        $reflection = new ReflectionEnum($this->enumClass);

        foreach ($reflection->getCases() as $caseReflection) {
            if ($caseReflection->getName() === (string) $value) {
                return $caseReflection->getValue();
            }
        }

        throw new InvalidArgumentException(sprintf(
            'Value "%s" is not a valid case name for enum %s.',
            (string) $value,
            $this->enumClass
        ));
    }
}
