<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Contract\SchemaExporterInterface;
use Iriven\PhpFormGenerator\Domain\Form\Form;

final class FormSchemaManager
{
    public function __construct(private readonly SchemaExporterInterface $exporter)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function export(Form $form): array
    {
        return $this->exporter->export($form);
    }
}
