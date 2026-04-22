<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class SchemaRuntimeContextTest extends TestCase
{
    public function testSchemaExportCanIncludeRuntimeMetadata(): void
    {
        $builder = (new FormFactory())->createBuilder('contact', null, ['method' => 'POST']);
        $builder->add('name', 'TextType');
        $form = $builder->getForm();

        $runtime = new FormRuntimeContext($form, 'tailwind', 'RendererClass', ['variant' => 'compact']);
        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($form, $runtime);

        self::assertArrayHasKey('runtime', $schema);
        self::assertSame('tailwind', $schema['runtime']['theme']);
    }
}
