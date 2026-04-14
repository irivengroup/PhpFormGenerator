<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Infrastructure\Mapping\ArrayDataMapper;
use Iriven\PhpFormGenerator\Infrastructure\Mapping\ObjectDataMapper;

final class FormDataMappingProcessor
{
    public function map(Form $form): void
    {
        $data = $form->rawData();
        $values = $form->submittedValues();

        if (is_array($data) || $data === null) {
            $mapper = new ArrayDataMapper();
            $form->replaceData($mapper->map(is_array($data) ? $data : [], $values));
            return;
        }

        if (is_object($data)) {
            $mapper = new ObjectDataMapper();
            $form->replaceData($mapper->map($data, $values));
        }
    }
}
