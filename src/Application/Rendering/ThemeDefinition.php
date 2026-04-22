<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Application\Rendering;
/** @api */
final class ThemeDefinition
{
    /** @param array<string, string> $components */
    public function __construct(
        private readonly string $name,
        private readonly ?string $parent = null,
        private readonly array $components = [],
    ) {}
    public function name(): string { return $this->name; }
    public function parent(): ?string { return $this->parent; }
    /** @return array<string, string> */
    public function components(): array { return $this->components; }
}
