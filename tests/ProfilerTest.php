<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Profiling\Profiler;
use PHPUnit\Framework\TestCase;

final class ProfilerTest extends TestCase
{
    public function testProfilerBuildsTimelineReport(): void
    {
        $profiler = new Profiler();
        $profiler->record('build', 1.2, 1024);

        $report = $profiler->report();

        self::assertArrayHasKey('timeline', $report);
        self::assertArrayHasKey('peak_memory_bytes', $report);
        self::assertSame('build', $report['timeline'][0]['step']);
    }
}
