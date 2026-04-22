<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Integration\Symfony;

/** @api */
final class SymfonyBundleConfig
{
    public function __construct(
        private readonly bool $autoRegisterCommands = true,
        private readonly bool $autoRegisterThemes = true,
    ) {}

    public function autoRegisterCommands(): bool { return $this->autoRegisterCommands; }
    public function autoRegisterThemes(): bool { return $this->autoRegisterThemes; }
}
