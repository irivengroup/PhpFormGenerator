<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Type;

use Iriven\Fluxon\Infrastructure\Registry\InMemoryFieldTypeRegistry;
use Iriven\Fluxon\Infrastructure\Registry\InMemoryFormTypeRegistry;

final class TypeResolver
{
    private static ?InMemoryFieldTypeRegistry $fieldTypeRegistry = null;
    private static ?InMemoryFormTypeRegistry $formTypeRegistry = null;

    public static function useFieldTypeRegistry(?InMemoryFieldTypeRegistry $registry): void
    {
        self::$fieldTypeRegistry = $registry;
    }

    public static function useFormTypeRegistry(?InMemoryFormTypeRegistry $registry): void
    {
        self::$formTypeRegistry = $registry;
    }

    public static function useRegistries(?InMemoryFieldTypeRegistry $fieldRegistry, ?InMemoryFormTypeRegistry $formRegistry): void
    {
        self::$fieldTypeRegistry = $fieldRegistry;
        self::$formTypeRegistry = $formRegistry;
    }

    public static function resolveFieldType(string $typeClass): string
    {
        if (class_exists($typeClass) || interface_exists($typeClass)) {
            return $typeClass;
        }

        $shortName = self::shortName($typeClass);
        $runtimeResolved = self::$fieldTypeRegistry?->resolve($shortName);

        return $runtimeResolved ?? BuiltinTypeRegistry::fieldTypes()[$shortName] ?? $typeClass;
    }

    public static function resolveFormType(string $typeClass): string
    {
        if (class_exists($typeClass) || interface_exists($typeClass)) {
            return $typeClass;
        }

        $shortName = self::shortName($typeClass);
        $runtimeResolved = self::$formTypeRegistry?->resolve($shortName);

        return $runtimeResolved ?? BuiltinTypeRegistry::formTypes()[$shortName] ?? $typeClass;
    }

    private static function shortName(string $typeClass): string
    {
        $position = strrpos($typeClass, '\\');

        return $position === false ? $typeClass : substr($typeClass, $position + 1);
    }
}
