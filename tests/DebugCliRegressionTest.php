<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Cli\DebugRuntimeCommand;
use Iriven\Fluxon\Application\Cli\DebugSchemaCommand;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormPluginKernel;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class DebugCliRegressionTest extends TestCase
{
    public function testDebugSchemaReturnsValidJson(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $output = (new DebugSchemaCommand(new FormSchemaManager(new ArraySchemaExporter()), $form))->run();

        self::assertNotFalse(json_decode($output, true));
    }

    public function testDebugRuntimeReturnsValidJson(): void
    {
        $output = (new DebugRuntimeCommand(new FormPluginKernel()))->run();

        self::assertNotFalse(json_decode($output, true));
    }
}
