<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Cli;

use Iriven\Fluxon\Integration\Laravel\LaravelServiceProviderConfig;
use Iriven\Fluxon\Integration\Symfony\SymfonyBundleConfig;

/** @api */
final class DebugIntegrationCommand implements CliCommandInterface
{
    public function name(): string
    {
        return 'debug:integration';
    }

    /**
     * @param array<int, string> $args
     */
    public function run(array $args = []): string
    {
        return json_encode([
            'symfony' => [
                'auto_register_commands' => (new SymfonyBundleConfig())->autoRegisterCommands(),
                'auto_register_themes' => (new SymfonyBundleConfig())->autoRegisterThemes(),
            ],
            'laravel' => [
                'publish_config' => (new LaravelServiceProviderConfig())->publishConfig(),
                'register_commands' => (new LaravelServiceProviderConfig())->registerCommands(),
            ],
        ], JSON_PRETTY_PRINT) ?: '{}';
    }
}
