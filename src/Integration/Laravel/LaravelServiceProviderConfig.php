<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Integration\Laravel;

/** @api */
final class LaravelServiceProviderConfig
{
    public function __construct(
        private readonly bool $publishConfig = true,
        private readonly bool $registerCommands = true,
    ) {}

    public function publishConfig(): bool { return $this->publishConfig; }
    public function registerCommands(): bool { return $this->registerCommands; }
}
