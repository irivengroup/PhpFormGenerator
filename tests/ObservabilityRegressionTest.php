<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Observability\InMemoryMetricsCollector;
use Iriven\Fluxon\Application\Observability\StructuredLogger;
use PHPUnit\Framework\TestCase;

final class ObservabilityRegressionTest extends TestCase
{
    public function testMetricsCollectorCanReportMissingMetric(): void
    {
        $collector = new InMemoryMetricsCollector();
        self::assertFalse($collector->has('missing'));
    }

    public function testStructuredLoggerCountStaysStable(): void
    {
        $logger = new StructuredLogger();
        $logger->log('built');
        $logger->log('rendered');
        self::assertSame(2, $logger->count());
    }
}
