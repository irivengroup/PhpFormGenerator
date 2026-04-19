<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Extension;

use Iriven\PhpFormGenerator\Domain\Contract\ExtensionInterface;
use Throwable;

/**
 * @api
 */
final class ExtensionRegistry
{
    /** @var list<object> */
    private array $fieldTypeExtensions = [];

    /** @var list<object> */
    private array $formTypeExtensions = [];

    public function addFieldExtension(ExtensionInterface $extension): void
    {
        $this->fieldTypeExtensions[] = $extension;
    }

    /**
     * Legacy-compatible alias.
     */
    public function addFieldTypeExtension(object $extension): void
    {
        $this->fieldTypeExtensions[] = $extension;
    }

    public function addFormExtension(object $extension): void
    {
        $this->formTypeExtensions[] = $extension;
    }

    /**
     * @return list<object>
     */
    public function all(): array
    {
        return array_values(array_merge($this->fieldTypeExtensions, $this->formTypeExtensions));
    }

    /**
     * New typed accessor.
     *
     * @return list<ExtensionInterface>
     */
    public function for(string $type): array
    {
        return array_values(array_filter(
            $this->fieldTypeExtensions,
            static function (object $extension) use ($type): bool {
                if (!$extension instanceof ExtensionInterface) {
                    return false;
                }

                try {
                    return $extension->supports($type);
                } catch (Throwable) {
                    return false;
                }
            }
        ));
    }

    /**
     * Legacy-compatible accessor used by form field factories.
     *
     * @return list<object>
     */
    public function fieldExtensionsFor(string $type): array
    {
        return array_values(array_filter(
            $this->fieldTypeExtensions,
            static function (object $extension) use ($type): bool {
                if ($extension instanceof ExtensionInterface) {
                    try {
                        return $extension->supports($type);
                    } catch (Throwable) {
                        return false;
                    }
                }

                if (method_exists($extension, 'supports')) {
                    try {
                        return (bool) $extension->supports($type);
                    } catch (Throwable) {
                        return false;
                    }
                }

                return true;
            }
        ));
    }

    /**
     * Legacy-compatible accessor used by form factories.
     *
     * @return list<object>
     */
    public function formExtensions(): array
    {
        return $this->formTypeExtensions;
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function apply(string $type, array $options): array
    {
        foreach ($this->for($type) as $extension) {
            try {
                $next = $extension->apply($options);
                if (is_array($next)) {
                    $options = $next;
                }
            } catch (Throwable) {
                // Non-destructive runtime hardening: ignore faulty extensions.
            }
        }

        return $options;
    }
}
