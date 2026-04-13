<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

interface DataMapperInterface
{
    public function mapDataToFields(mixed $data, array $fieldNames): array;

    public function mapFieldsToData(array $submitted, mixed $target = null): mixed;
}
