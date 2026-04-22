<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Profiling;

/** @api */
final class ProfileTimeline
{
    /** @var array<int, array<string, float|int|string>> */
    private array $steps = [];

    public function add(string $step, float $timeMs, int $memoryBytes = 0): void
    {
        $this->steps[] = [
            'step' => $step,
            'time_ms' => $timeMs,
            'memory_bytes' => $memoryBytes,
        ];
    }

    /**
     * @return array<int, array<string, float|int|string>>
     */
    public function all(): array
    {
        return $this->steps;
    }
}
