<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

interface FieldTypeInterface
{
    public function renderType(): string;
}
