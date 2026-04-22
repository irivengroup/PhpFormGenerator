<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Dx;

/** @api */
final class DeveloperExperienceConfig
{
    public function __construct(
        private readonly bool $strictMode = true,
        private readonly bool $debug = false,
        private readonly bool $cacheEnabled = true,
    ) {}

    public function strictMode(): bool { return $this->strictMode; }
    public function debug(): bool { return $this->debug; }
    public function cacheEnabled(): bool { return $this->cacheEnabled; }
}
