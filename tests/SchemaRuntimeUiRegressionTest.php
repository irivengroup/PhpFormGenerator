<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class SchemaRuntimeUiRegressionTest extends TestCase
{
    public function testSchemaWithoutRuntimeContextDoesNotExposeRuntimeOrUi(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');
        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($builder->getForm());

        self::assertArrayNotHasKey('runtime', $schema);
        self::assertArrayNotHasKey('ui', $schema);
    }

    public function testSchemaWithRuntimeContextIncludesUiVariant(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');
        $form = $builder->getForm();

        $runtime = new FormRuntimeContext($form, 'tailwind', 'RendererClass', ['variant' => 'compact']);
        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($form, $runtime);

        self::assertSame('compact', $schema['ui']['variant']);
    }
}
