<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormSchemaManager;
use Iriven\PhpFormGenerator\Application\Frontend\FrontendSdk;
use Iriven\PhpFormGenerator\Application\Frontend\FrontendSdkConfig;
use Iriven\PhpFormGenerator\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FrontendSdkPostReleaseRegressionTest extends TestCase
{
    public function testSdkPublicAccessorsRemainStable(): void
    {
        $sdk = new FrontendSdk(
            new FormSchemaManager(new ArraySchemaExporter()),
            new FrontendSdkConfig('generic', '2.0')
        );

        self::assertSame('generic', $sdk->getFramework());
        self::assertSame('2.0', $sdk->getSchemaVersion());
    }

    public function testSdkSchemaContainsSdkMetadataPostRelease(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()));

        $schema = $sdk->buildSchema($form);

        self::assertArrayHasKey('sdk', $schema);
        self::assertSame('2.0', $schema['sdk']['schema_version']);
    }
}
