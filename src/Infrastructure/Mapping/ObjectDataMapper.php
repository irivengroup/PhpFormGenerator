<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Mapping;

use Iriven\PhpFormGenerator\Domain\Contract\DataMapperInterface;

final class ObjectDataMapper implements DataMapperInterface
{
    public function mapDataToFields(mixed $data, array $fieldNames): array
    {
        if (!is_object($data)) {
            return [];
        }

        $mapped = [];
        foreach ($fieldNames as $name) {
            $mapped[$name] = property_exists($data, $name) ? $data->{$name} : null;
        }

        return $mapped;
    }

    public function mapFieldsToData(array $submitted, mixed $target = null): mixed
    {
        $object = is_object($target) ? $target : new \stdClass();
        foreach ($submitted as $name => $value) {
            $object->{$name} = $value;
        }

        return $object;
    }
}
