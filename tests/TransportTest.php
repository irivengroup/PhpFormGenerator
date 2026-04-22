<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Runtime\HttpTransport;
use Iriven\Fluxon\Application\Runtime\InMemoryTransport;
use PHPUnit\Framework\TestCase;

final class TransportTest extends TestCase
{
    public function testInMemoryTransportReturnsPayload(): void
    {
        self::assertSame(['a' => 1], (new InMemoryTransport())->send(['a' => 1]));
    }

    public function testHttpTransportWrapsPayload(): void
    {
        self::assertSame('http', (new HttpTransport())->send(['a' => 1])['transport']);
    }
}
