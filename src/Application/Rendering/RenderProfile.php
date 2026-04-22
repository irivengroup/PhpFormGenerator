<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Rendering;

/**
 * @api
 */
final class RenderProfile
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        private readonly string $theme = 'default',
        private readonly string $channel = RenderingChannel::HTML,
        private readonly array $metadata = [],
    ) {
    }

    public function theme(): string
    {
        return trim($this->theme) !== '' ? $this->theme : 'default';
    }

    public function channel(): string
    {
        return trim($this->channel) !== '' ? $this->channel : RenderingChannel::HTML;
    }

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}
