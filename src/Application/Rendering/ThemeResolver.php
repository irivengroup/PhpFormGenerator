<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Application\Rendering;
/** @api */
final class ThemeResolver
{
    public function __construct(
        private readonly ThemeRegistry $registry = new ThemeRegistry(),
        private readonly string $fallbackTheme = 'default',
    ) {}
    public function resolve(string $name): string { return $this->registry->has($name) ? $name : $this->fallbackTheme; }
    /** @return array<string, string> */
    public function components(string $name): array
    {
        $resolved = $this->resolve($name);
        $theme = $this->registry->get($resolved);
        if (!$theme instanceof ThemeDefinition) { return []; }
        $components = [];
        $parent = $theme->parent();
        if (is_string($parent) && $parent !== '') { $components = $this->components($parent); }
        return array_merge($components, $theme->components());
    }
}
