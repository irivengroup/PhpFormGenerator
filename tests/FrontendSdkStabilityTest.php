<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FrontendSdkStabilityTest extends TestCase
{
    public function testSchemaContainsStableTopLevelKeys(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter())))->buildSchema($form);

        self::assertSame(
            ['form', 'fields', 'ui', 'runtime', 'validation', 'schema', 'sdk'],
            array_keys($schema)
        );
    }

    public function testSchemaAlwaysContainsSdkAndSchemaNodes(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter())))->buildSchema($form);

        self::assertArrayHasKey('schema', $schema);
        self::assertArrayHasKey('sdk', $schema);
    }
}
