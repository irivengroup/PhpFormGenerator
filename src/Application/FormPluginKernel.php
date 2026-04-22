<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application;

use Iriven\Fluxon\Application\Plugin\PluginValidator;
use Iriven\Fluxon\Domain\Contract\PluginInterface;
use Iriven\Fluxon\Infrastructure\Extension\ExtensionRegistry;
use Iriven\Fluxon\Infrastructure\Registry\BuiltinRegistries;
use Iriven\Fluxon\Infrastructure\Registry\InMemoryFieldTypeRegistry;
use Iriven\Fluxon\Infrastructure\Registry\InMemoryFormTypeRegistry;
use Iriven\Fluxon\Infrastructure\Registry\PluginRegistry;
use Iriven\Fluxon\Infrastructure\Type\TypeResolver;

final class FormPluginKernel
{
    private PluginRegistry $plugins;

    public function __construct(
        ?ExtensionRegistry $extensionRegistry = null,
        private readonly ?PluginValidator $pluginValidator = null,
    ) {
        $this->plugins = new PluginRegistry(
            BuiltinRegistries::fieldTypes(),
            BuiltinRegistries::formTypes(),
            $extensionRegistry ?? new ExtensionRegistry(),
        );

        $this->bootRuntime();
    }

    public function register(PluginInterface $plugin): self
    {
        ($this->pluginValidator ?? new PluginValidator())->validate($plugin);
        $this->plugins->registerPlugin($plugin);
        $this->bootRuntime();

        return $this;
    }

    public function plugins(): PluginRegistry
    {
        return $this->plugins;
    }

    public function registry(): PluginRegistry
    {
        return $this->plugins;
    }

    public function fieldTypes(): InMemoryFieldTypeRegistry
    {
        return $this->plugins->fieldTypes();
    }

    public function formTypes(): InMemoryFormTypeRegistry
    {
        return $this->plugins->formTypes();
    }

    public function extensions(): ExtensionRegistry
    {
        return $this->plugins->extensions();
    }

    private function bootRuntime(): void
    {
        TypeResolver::useRegistries($this->fieldTypes(), $this->formTypes());
    }
}
