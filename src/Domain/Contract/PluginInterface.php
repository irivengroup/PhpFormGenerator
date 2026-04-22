<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

use Iriven\Fluxon\Infrastructure\Extension\ExtensionRegistry;

/**
 * @api
 */
interface PluginInterface
{
    public function registerFieldTypes(FieldTypeRegistryInterface $registry): void;

    public function registerFormTypes(FormTypeRegistryInterface $registry): void;

    public function registerExtensions(ExtensionRegistry $registry): void;
}
