<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\DebugDtoMetadataCommand;
use PHPUnit\Framework\TestCase;

final class DtoMetadataCliCommandTest extends TestCase
{
    public function testDebugDtoMetadataReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new DebugDtoMetadataCommand())->run(), true));
    }
}
