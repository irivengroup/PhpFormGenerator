<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Extension;

use Iriven\Fluxon\Domain\Contract\ExtensionInterface;
use Iriven\Fluxon\Domain\Contract\FieldTypeExtensionInterface;

/**
 * @api
 */
final class ExtensionRegistry
{
    /** @var array<int, object> */
    private array $fieldTypeExtensions = [];

    /** @var array<int, object> */
    private array $formTypeExtensions = [];

    public function addFieldTypeExtension(object $extension): void
    {
        $this->fieldTypeExtensions[] = $extension;
    }

    public function addFieldExtension(object $extension): void
    {
        $this->addFieldTypeExtension($extension);
    }

    public function addFormTypeExtension(object $extension): void
    {
        $this->formTypeExtensions[] = $extension;
    }

    /**
     * @return array<int, object>
     */
    public function all(): array
    {
        return array_values(array_merge($this->fieldTypeExtensions, $this->formTypeExtensions));
    }

    /**
     * Generic registry resolution for ExtensionInterface-based extensions.
     *
     * @return array<int, ExtensionInterface>
     */
    public function for(string $type): array
    {
        $resolved = [];

        foreach ($this->fieldTypeExtensions as $extension) {
            if (!$extension instanceof ExtensionInterface) {
                continue;
            }

            try {
                if ($extension->supports($type)) {
                    $resolved[] = $extension;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return array_values($resolved);
    }

    /**
     * Applies generic ExtensionInterface extensions in registration order.
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function apply(string $type, array $options): array
    {
        $resolved = $options;

        foreach ($this->for($type) as $extension) {
            try {
                $resolved = $extension->apply($resolved);
            } catch (\Throwable) {
                continue;
            }
        }

        return $resolved;
    }

    /**
     * Resolves field-type extensions used by the form runtime.
     *
     * @return array<int, FieldTypeExtensionInterface>
     */
    public function fieldExtensionsFor(string $type): array
    {
        $resolved = [];

        foreach ($this->fieldTypeExtensions as $extension) {
            if (!$extension instanceof FieldTypeExtensionInterface) {
                continue;
            }

            try {
                if ($extension::getExtendedType() === $type) {
                    $resolved[] = $extension;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return array_values($resolved);
    }

    /**
     * @return array<int, object>
     */
    public function formExtensions(): array
    {
        return array_values($this->formTypeExtensions);
    }
}
