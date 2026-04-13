<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FieldTypeInterface;

final class FieldDefinition
{
    /** @param list<ConstraintInterface> $constraints */
    public function __construct(
        public readonly string $name,
        public readonly FieldTypeInterface $type,
        public readonly array $options = [],
        public readonly array $constraints = [],
        public mixed $value = null,
        public array $errors = [],
    ) {
    }
}
