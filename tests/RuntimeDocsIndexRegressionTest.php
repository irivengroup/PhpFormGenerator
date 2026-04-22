<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use PHPUnit\Framework\TestCase;

final class RuntimeDocsIndexRegressionTest extends TestCase
{
    public function testDocsIndexReferencesRuntimePages(): void
    {
        $content = file_get_contents(__DIR__ . '/../docs/index.md');
        self::assertIsString($content);
        self::assertStringContainsString('runtime.md', $content);
        self::assertStringContainsString('lifecycle.md', $content);
        self::assertStringContainsString('runtime-hooks.md', $content);
        self::assertStringContainsString('runtime-static-conformance.md', $content);
    }
}
