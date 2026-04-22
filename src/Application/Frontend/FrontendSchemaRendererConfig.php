<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Frontend;

/**
 * @api
 */
final class FrontendSchemaRendererConfig
{
    /**
     * @param array<string, mixed> $defaultProps
     */
    public function __construct(
        private readonly UiComponentMap $componentMap = new UiComponentMap(),
        private readonly array $defaultProps = [],
    ) {
    }

    public function componentMap(): UiComponentMap
    {
        return $this->componentMap;
    }

    /**
     * @return array<string, mixed>
     */
    public function defaultProps(): array
    {
        return $this->defaultProps;
    }
}
