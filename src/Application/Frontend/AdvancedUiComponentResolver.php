<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Frontend;

/**
 * @api
 */
final class AdvancedUiComponentResolver
{
    public function __construct(
        private readonly UiComponentResolver $baseResolver = new UiComponentResolver(),
        private readonly UiComponentMap $componentMap = new UiComponentMap(),
    ) {
    }

    public function resolve(string $fieldType): string
    {
        return $this->componentMap->resolve($fieldType, $this->baseResolver->resolve($fieldType));
    }
}
