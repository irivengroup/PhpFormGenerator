<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FormSchemaManagerScopeRegressionTest extends TestCase
{
    public function testStandardExportRemainsUnversioned(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($form);

        self::assertArrayNotHasKey('schema', $schema);
    }

    public function testHeadlessExportRemainsVersioned(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->exportHeadless($form);

        self::assertSame('2.1', $schema['schema']['version']);
    }
}
