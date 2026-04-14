<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

final class FormViewFactory
{
    /**
     * @param array<string, mixed> $vars
     * @param array<int, FormView> $children
     * @param array<int, string> $errors
     * @param array<int, Fieldset> $fieldsets
     */
    public function create(
        string $name,
        string $fullName,
        string $id,
        string $type,
        mixed $value,
        array $vars = [],
        array $children = [],
        array $errors = [],
        array $fieldsets = [],
        bool $submitted = false,
        bool $valid = true,
    ): FormView {
        return new FormView($name, $fullName, $id, $type, $value, $vars, $children, $errors, $fieldsets, $submitted, $valid);
    }
}
