<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Cli;

use Iriven\Fluxon\Application\Diagnostics\DiagnosticsRunner;

/** @api */
final class DebugDiagnosticsCommand implements CliCommandInterface
{
    public function name(): string
    {
        return 'debug:diagnostics';
    }

    /**
     * @param array<int, string> $args
     */
    public function run(array $args = []): string
    {
        return json_encode((new DiagnosticsRunner())->run(), JSON_PRETTY_PRINT) ?: '{}';
    }
}
