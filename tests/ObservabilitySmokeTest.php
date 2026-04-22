<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Observability\InMemoryMetricsCollector;
use Iriven\Fluxon\Application\Observability\StructuredLogger;
use PHPUnit\Framework\TestCase;

final class ObservabilitySmokeTest extends TestCase
{
    public function testMetricsAndLogsCanBeCollected(): void
    {
        $metrics = new InMemoryMetricsCollector();
        $metrics->record('build', 1.2);

        $logger = new StructuredLogger();
        $logger->log('built', ['form' => 'contact']);

        self::assertSame(1.2, $metrics->all()['build']);
        self::assertSame('built', $logger->entries()[0]['message']);
    }
}
