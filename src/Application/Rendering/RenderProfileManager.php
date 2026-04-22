<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Rendering;

/**
 * @api
 */
final class RenderProfileManager
{
    public function __construct(
        private readonly ThemeResolver $themeResolver = new ThemeResolver(),
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function export(RenderProfile $profile): array
    {
        $resolvedTheme = $this->themeResolver->resolve($profile->theme());
        $metadata = $profile->metadata();

        return [
            'theme' => $resolvedTheme,
            'channel' => $profile->channel(),
            'theme_components' => $this->themeResolver->components($resolvedTheme),
            'metadata' => $metadata,
        ];
    }
}
