<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

use Iriven\PhpFormGenerator\Domain\Form\Field;

interface FieldTypeInterface
{
    public function name(): string;

    public function configureOptions(array $options = []): array;

    public function buildField(string $name, array $options = []): Field;

    public function normalizeSubmittedValue(mixed $value, array $options = []): mixed;

    public function buildViewVariables(Field $field): array;
}
