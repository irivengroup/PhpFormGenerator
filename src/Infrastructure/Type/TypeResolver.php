<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Type;

use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;

final class TypeResolver
{
    /**
     * @param class-string|string $typeClass
     * @return class-string|string
     */
    public static function resolveFieldType(string $typeClass): string
    {
        if (class_exists($typeClass) || interface_exists($typeClass)) {
            return $typeClass;
        }

        $shortName = self::shortName($typeClass);

        return BuiltinTypeRegistry::fieldTypes()[$shortName] ?? $typeClass;
    }

    /**
     * @param class-string<FormTypeInterface>|string $typeClass
     * @return class-string<FormTypeInterface>|string
     */
    public static function resolveFormType(string $typeClass): string
    {
        if (class_exists($typeClass) || interface_exists($typeClass)) {
            return $typeClass;
        }

        $shortName = self::shortName($typeClass);

        /** @var class-string<FormTypeInterface>|string $resolved */
        $resolved = BuiltinTypeRegistry::formTypes()[$shortName] ?? $typeClass;

        return $resolved;
    }

    private static function shortName(string $typeClass): string
    {
        $position = strrpos($typeClass, '\\');

        return $position === false ? $typeClass : substr($typeClass, $position + 1);
    }
}
