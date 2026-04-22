<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendFrameworkPresets;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FrontendSdkMaintenanceTest extends TestCase
{
    public function testSchemaIsNormalizedWhenRuntimeContextIsNull(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()), FrontendFrameworkPresets::react());

        $schema = $sdk->buildSchema($form, null);

        self::assertArrayHasKey('form', $schema);
        self::assertArrayHasKey('fields', $schema);
        self::assertArrayHasKey('ui', $schema);
        self::assertArrayHasKey('runtime', $schema);
        self::assertArrayHasKey('validation', $schema);
    }

    public function testPayloadIsNormalizedWhenDataIsEmpty(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()), FrontendFrameworkPresets::vue());

        $payload = $sdk->buildSubmissionPayload($form, []);

        self::assertSame([], $payload['payload']);
    }
}
