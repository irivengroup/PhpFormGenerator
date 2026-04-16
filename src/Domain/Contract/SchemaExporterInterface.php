<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

use Iriven\PhpFormGenerator\Domain\Form\Form;

interface SchemaExporterInterface
{
    /**
     * @return array<string, mixed>
     */
    public function export(Form $form): array;
}
