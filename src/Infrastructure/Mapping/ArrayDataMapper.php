<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Mapping;

use Iriven\PhpFormGenerator\Domain\Form\Form;

final class ArrayDataMapper
{
    public function map(Form $form): array
    {
        $data = [];
        foreach ($form->fields() as $field) {
            $data[$field->name] = $field->value;
        }
        return $data;
    }
}
