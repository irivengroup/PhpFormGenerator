<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Transformer;

use BackedEnum;
use InvalidArgumentException;
use Iriven\Fluxon\Domain\Contract\DataTransformerInterface;
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
            return $this->unitEnumName($value);
        }

        return $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        if ($value instanceof UnitEnum) {
            return $value;
        }

        $this->assertEnumExists();

        return $this->isBackedEnum()
            ? $this->reverseTransformBackedEnum($value)
            : $this->reverseTransformUnitEnum((string) $value);
    }

    private function isEmptyValue(mixed $value): bool
    {
        return $value === null || $value === '';
    }

    private function assertEnumExists(): void
    {
        if (!enum_exists($this->enumClass)) {
            throw new InvalidArgumentException('Enum class does not exist: ' . $this->enumClass);
        }
    }

    private function isBackedEnum(): bool
    {
        return is_subclass_of($this->enumClass, BackedEnum::class);
    }

    private function reverseTransformBackedEnum(mixed $value): BackedEnum
    {
        /** @var string $backedEnumClass */
        $backedEnumClass = $this->enumClass;

        return $backedEnumClass::from($value);
    }

    private function reverseTransformUnitEnum(string $value): UnitEnum
    {
        $case = $this->findUnitEnumCaseByName($this->enumClass, $value);

        if ($case !== null) {
            return $case;
        }

        throw new InvalidArgumentException(sprintf(
            'Value "%s" is not a valid case name for enum %s.',
            $value,
            $this->enumClass
        ));
    }

    /**
     * @param string $unitEnumClass
     */
    private function findUnitEnumCaseByName(string $unitEnumClass, string $name): ?UnitEnum
    {
        foreach ($unitEnumClass::cases() as $case) {
            if (!$case instanceof UnitEnum) {
                continue;
            }

            if ($this->unitEnumName($case) === $name) {
                return $case;
            }
        }

        return null;
    }

    private function unitEnumName(UnitEnum $case): string
    {
        /** @var object{name:string} $case */
        return $case->name;
    }
}
