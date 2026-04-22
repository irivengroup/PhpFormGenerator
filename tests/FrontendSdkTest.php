<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendFrameworkPresets;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class FrontendSdkTest extends TestCase
{
    public function testSdkBuildsSchemaWithSdkMetadata(): void
    {
        $builder = (new FormFactory())->createBuilder('contact', null, ['method' => 'POST']);
        $builder->add('name', 'TextType', ['required' => true]);
        $form = $builder->getForm();

        $runtime = new FormRuntimeContext($form, 'tailwind', 'RendererClass', ['variant' => 'compact']);
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()), FrontendFrameworkPresets::react());

        $schema = $sdk->buildSchema($form, $runtime);

        self::assertArrayHasKey('sdk', $schema);
        self::assertSame('react', $schema['sdk']['framework']);
        self::assertSame('1.0', $schema['sdk']['schema_version']);
    }

    public function testSdkBuildsSubmissionPayload(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()), FrontendFrameworkPresets::vue());

        $payload = $sdk->buildSubmissionPayload($form, ['name' => 'Alice']);

        self::assertSame('contact', $payload['form']);
        self::assertSame(['name' => 'Alice'], $payload['payload']);
        self::assertSame('vue', $payload['sdk']['framework']);
    }
}
