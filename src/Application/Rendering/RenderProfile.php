<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Application\Rendering;
/** @api */
final class RenderProfile
{
    /** @param array<string, mixed> $metadata */
    public function __construct(
        private readonly string $theme = 'default',
        private readonly string $channel = RenderingChannel::HTML,
        private readonly array $metadata = [],
    ) {}
    public function theme(): string { return $this->theme; }
    public function channel(): string { return $this->channel; }
    /** @return array<string, mixed> */
    public function metadata(): array { return $this->metadata; }
}
