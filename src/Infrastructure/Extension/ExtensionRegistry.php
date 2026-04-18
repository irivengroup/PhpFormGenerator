<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Extension;

use Iriven\PhpFormGenerator\Domain\Contract\ExtensionInterface;

final class ExtensionRegistry
{
    /** @var list<ExtensionInterface> */
    private array $extensions = [];

    public function addFieldExtension(ExtensionInterface $extension): void
    {
        $this->extensions[] = $extension;
    }

    /** @return list<ExtensionInterface> */
    public function all(): array
    {
        return $this->extensions;
    }

    /** @return list<ExtensionInterface> */
    public function for(string $type): array
    {
        return array_values(array_filter(
            $this->extensions,
            fn (ExtensionInterface $ext) => $ext->supports($type)
        ));
    }
}
