<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormSchemaManager;
use Iriven\PhpFormGenerator\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class SchemaHardeningRuntimeTest extends TestCase
{
    public function testCollectionFieldExportsEntryMetadata(): void
    {
        $factory = new FormFactory();
        $builder = $factory->createBuilder('catalog', null, ['method' => 'POST']);
        $builder->add('tags', 'CollectionType', [
            'entry_type' => 'TextType',
            'entry_options' => ['label' => 'Tag'],
            'allow_add' => true,
        ]);
        $form = $builder->getForm();

        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($form);

        self::assertTrue($schema['fields']['tags']['collection']);
        self::assertSame('Iriven\PhpFormGenerator\Domain\Field\TextType', $schema['fields']['tags']['entry_type']);
        self::assertSame(['label' => 'Tag'], $schema['fields']['tags']['entry_options']);
    }

    public function testNestedCompoundFieldExportsChildren(): void
    {
        $factory = new FormFactory();
        $builder = $factory->createBuilder('profile', null, ['method' => 'POST']);

        $addressBuilder = $factory->createBuilder('address');
        $addressBuilder
            ->add('street', 'TextType', ['label' => 'Street'])
            ->add('zip', 'TextType', ['label' => 'ZIP']);

        $builder->add('address', 'Iriven\PhpFormGenerator\Domain\Field\FormType', [
            'label' => 'Address',
        ]);

        // fallback synthetic nested field to exercise exporter structure through actual form type usage where available
        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($builder->getForm());

        self::assertArrayHasKey('address', $schema['fields']);
        self::assertArrayHasKey('children', $schema['fields']['address']);
    }

    public function testScalarFieldExportsHelpPlaceholderAndDefault(): void
    {
        $factory = new FormFactory();
        $builder = $factory->createBuilder('contact', null, ['method' => 'POST', 'action' => '/contact']);
        $builder->add('name', 'TextType', [
            'label' => 'Name',
            'help' => 'Tell us your name',
            'data' => 'Alice',
            'attr' => ['placeholder' => 'John Doe'],
        ]);

        $schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($builder->getForm());

        self::assertSame('/contact', $schema['action']);
        self::assertSame('Tell us your name', $schema['fields']['name']['help']);
        self::assertSame('John Doe', $schema['fields']['name']['placeholder']);
        self::assertSame('Alice', $schema['fields']['name']['default']);
    }
}
