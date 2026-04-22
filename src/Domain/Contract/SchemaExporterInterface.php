<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

use Iriven\Fluxon\Domain\Form\Form;

interface SchemaExporterInterface
{
    /**
     * @return array<string, mixed>
     */
    public function export(Form $form): array;
}
