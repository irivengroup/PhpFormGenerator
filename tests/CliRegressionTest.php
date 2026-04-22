<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\CliApplication;
use Iriven\Fluxon\Application\Cli\MakeFormCommand;
use Iriven\Fluxon\Application\Cli\MakePluginCommand;
use PHPUnit\Framework\TestCase;

final class CliRegressionTest extends TestCase
{
    public function testCommandsListIsSorted(): void
    {
        $cli = new CliApplication([new MakePluginCommand(), new MakeFormCommand()]);

        self::assertSame(['make:form', 'make:plugin'], $cli->commands());
    }

    public function testMakeFormFallsBackToDefaultName(): void
    {
        $output = (new MakeFormCommand())->run([]);

        self::assertStringContainsString('GeneratedFormType', $output);
    }

    public function testMakePluginFallsBackToDefaultName(): void
    {
        $output = (new MakePluginCommand())->run([]);

        self::assertStringContainsString('GeneratedPlugin', $output);
    }
}
