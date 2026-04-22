<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Mapping\FormHydrator;
use PHPUnit\Framework\TestCase;

final class MappingEdgeCaseTest extends TestCase
{
    public function testEmptyPayloadReturnsTarget(): void
    {
        $result = (new FormHydrator())->hydrate([], ['name' => 'John']);
        self::assertSame('John', $result['name']);
    }

    public function testTrimmedKeysAreNormalized(): void
    {
        $result = (new FormHydrator())->hydrate([' email ' => 'x']);
        self::assertArrayHasKey('email', $result);
    }
}
