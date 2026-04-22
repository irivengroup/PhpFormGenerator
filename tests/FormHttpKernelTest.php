<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Server\Http\FormHttpKernel;
use PHPUnit\Framework\TestCase;

final class FormHttpKernelTest extends TestCase
{
    public function testHealthEndpointReturnsOk(): void
    {
        self::assertSame('ok', (new FormHttpKernel())->handle('GET', '/health')['status']);
    }

    public function testSchemaEndpointReturnsJsonShape(): void
    {
        $r = (new FormHttpKernel())->handle('GET', '/forms/contact/schema');
        self::assertSame('contact', $r['name']);
        self::assertArrayHasKey('schema', $r);
    }
}
