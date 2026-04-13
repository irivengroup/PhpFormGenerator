<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Mapping;

use Iriven\PhpFormGenerator\Domain\Contract\DataMapperInterface;

final class ArrayDataMapper implements DataMapperInterface
{
    public function mapDataToFields(mixed $data, array $fieldNames): array
    {
        if (!is_array($data)) {
            return [];
        }

        $mapped = [];
        foreach ($fieldNames as $name) {
            $mapped[$name] = $data[$name] ?? null;
        }

        return $mapped;
    }

    public function mapFieldsToData(array $submitted, mixed $target = null): mixed
    {
        return array_merge(is_array($target) ? $target : [], $submitted);
    }
}
