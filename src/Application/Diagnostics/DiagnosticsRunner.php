<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Diagnostics;

use Iriven\Fluxon\Application\Profiling\Profiler;

/** @api */
final class DiagnosticsRunner
{
    public function __construct(
        private readonly ?Profiler $profiler = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function run(): array
    {
        $profiler = $this->profiler ?? new Profiler();
        $profiler->record('bootstrap', 0.1);
        $profiler->record('runtime_check', 0.2);

        return [
            'status' => 'ok',
            'issues' => [],
            'warnings' => [],
            'performance' => $profiler->report(),
        ];
    }
}
