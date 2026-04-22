<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\Cli\DebugHeadlessContractCommand;
use Iriven\Fluxon\Application\Cli\DebugHeadlessSubmissionCommand;
use PHPUnit\Framework\TestCase;
final class HeadlessCliCommandTest extends TestCase
{
    public function testDebugHeadlessContractReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new DebugHeadlessContractCommand())->run(), true));
    }
    public function testDebugHeadlessSubmissionReturnsJson(): void
    {
        self::assertNotFalse(json_decode((new DebugHeadlessSubmissionCommand())->run(), true));
    }
}
