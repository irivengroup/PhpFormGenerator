<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

/**
 * @api
 */
interface ExtensionRegistryInterface
{
    public function addFieldExtension(object $extension): void;
}
