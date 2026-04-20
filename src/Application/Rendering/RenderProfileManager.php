<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Application\Rendering;
/** @api */
final class RenderProfileManager
{
    public function __construct(private readonly ThemeResolver $themeResolver = new ThemeResolver()) {}
    /** @return array<string, mixed> */
    public function export(RenderProfile $profile): array
    {
        return [
            'theme' => $this->themeResolver->resolve($profile->theme()),
            'channel' => $profile->channel(),
            'theme_components' => $this->themeResolver->components($profile->theme()),
            'metadata' => $profile->metadata(),
        ];
    }
}
