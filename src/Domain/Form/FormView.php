<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

final class FormView
{
    /** @param list<array<string,mixed>> $children */
    public function __construct(
        public readonly string $name,
        public readonly string $method,
        public readonly string $action,
        public readonly array $attributes,
        public readonly array $children,
        public readonly array $errors = []
    ) {
    }
}
