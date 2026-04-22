<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Rendering;

/**
 * @api
 */
final class ThemeResolver
{
    public function __construct(
        private readonly ThemeRegistry $registry = new ThemeRegistry(),
        private readonly string $fallbackTheme = 'default',
    ) {
    }

    public function resolve(string $name): string
    {
        $candidate = trim($name);

        if ($candidate !== '' && $this->registry->has($candidate)) {
            return $candidate;
        }

        return $this->fallbackTheme;
    }

    /**
     * @return array<string, string>
     */
    public function components(string $name): array
    {
        return $this->componentsForResolved($this->resolve($name), []);
    }

    /**
     * @param array<string, true> $visited
     * @return array<string, string>
     */
    private function componentsForResolved(string $name, array $visited): array
    {
        if (isset($visited[$name])) {
            return [];
        }

        $visited[$name] = true;

        $theme = $this->registry->get($name);
        if (!$theme instanceof ThemeDefinition) {
            return [];
        }

        $components = [];
        $parent = $theme->parent();

        if (is_string($parent) && $parent !== '' && $this->registry->has($parent)) {
            $components = $this->componentsForResolved($parent, $visited);
        }

        return array_merge($components, $theme->components());
    }
}
