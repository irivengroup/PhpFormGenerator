<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Runtime\ExecutionContext;
use Iriven\Fluxon\Application\Runtime\FormRuntimeEngine;
use PHPUnit\Framework\TestCase;

final class FormRuntimeEngineTest extends TestCase
{
    public function testRuntimeEngineCanExportSchema(): void
    {
        $result = (new FormRuntimeEngine())->schema('contact', new ExecutionContext('req-1', '2026-01-01T00:00:00+00:00', 'test'));
        self::assertSame('contact', $result['name']);
        self::assertArrayHasKey('schema', $result);
        self::assertArrayHasKey('context', $result);
    }

    public function testRuntimeEngineCanSubmitPayload(): void
    {
        $result = (new FormRuntimeEngine())->submit('contact', ['email' => 'john@example.com']);
        self::assertTrue($result['state']['submitted']);
    }
}
