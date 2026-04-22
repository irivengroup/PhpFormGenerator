<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests\Fixtures\Plugin;

use Iriven\Fluxon\Domain\Contract\FieldTypeRegistryInterface;
use Iriven\Fluxon\Domain\Contract\FormTypeRegistryInterface;
use Iriven\Fluxon\Domain\Contract\PluginInterface;
use Iriven\Fluxon\Infrastructure\Extension\ExtensionRegistry;

final class EmptyPlugin implements PluginInterface
{
    public function registerFieldTypes(FieldTypeRegistryInterface $registry): void
    {
    }

    public function registerFormTypes(FormTypeRegistryInterface $registry): void
    {
    }

    public function registerExtensions(ExtensionRegistry $registry): void
    {
    }

    public function register(\Iriven\Fluxon\Infrastructure\Registry\PluginRegistry $registry): void
    {
        $registry->register($this);
    }
}
