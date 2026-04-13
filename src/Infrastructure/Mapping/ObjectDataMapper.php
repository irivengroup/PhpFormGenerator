<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Mapping;

use Iriven\PhpFormGenerator\Domain\Form\Form;

final class ObjectDataMapper
{
    public function map(Form $form, object $target): object
    {
        foreach ($form->fields() as $field) {
            if (property_exists($target, $field->name)) {
                $target->{$field->name} = $field->value;
            }
        }
        return $target;
    }
}
