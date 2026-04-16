<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

use Iriven\PhpFormGenerator\Presentation\Html\Theme\ThemeInterface;

interface ThemeRegistryInterface
{
    public function register(string $alias, ThemeInterface $theme): void;

    public function has(string $alias): bool;

    public function resolve(string $alias): ?ThemeInterface;

    /**
     * @return array<string, ThemeInterface>
     */
    public function all(): array;
}
