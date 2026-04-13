<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Field\CollectionType;
use Iriven\PhpFormGenerator\Domain\Field\NumberType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Presentation\Html\HtmlRenderer;
use PHPUnit\Framework\TestCase;

final class NestedFormsAndCollectionsTest extends TestCase
{
    public function testNestedFormAndCollectionSubmission(): void
    {
        $factory = new FormFactory();
        $form = $factory->create(InvoiceType::class, null, 'invoice', ['method' => 'POST']);

        $form->handleRequest(new ArrayRequest('POST', [
            'invoice' => [
                'customer' => [
                    'name' => 'ACME',
                    'reference' => 'C-42',
                ],
                'items' => [
                    ['label' => 'Audit', 'quantity' => '2'],
                    ['label' => 'Build', 'quantity' => '5'],
                ],
            ],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid());

        $data = $form->data();
        self::assertIsArray($data);
        self::assertSame('ACME', $data['customer']['name']);
        self::assertCount(2, $data['items']);
        self::assertSame('Build', $data['items'][1]['label']);

        $html = (new HtmlRenderer())->render($form);
        self::assertStringContainsString('name="invoice[customer][name]"', $html);
        self::assertStringContainsString('name="invoice[items][0][label]"', $html);
        self::assertStringContainsString('data-prototype=', $html);
    }

    public function testNestedFieldErrorsBubbleThroughTree(): void
    {
        $factory = new FormFactory();
        $form = $factory->create(InvoiceType::class, null, 'invoice', ['method' => 'POST']);

        $form->handleRequest(new ArrayRequest('POST', [
            'invoice' => [
                'customer' => [
                    'name' => '',
                    'reference' => 'C-42',
                ],
                'items' => [
                    ['label' => '', 'quantity' => '2'],
                ],
            ],
        ]));

        self::assertFalse($form->isValid());
        self::assertTrue($form->hasErrors(true));
        self::assertNotEmpty($form->get('customer')->compoundForm()?->get('name')->errors);
        self::assertNotEmpty($form->get('items')->entries()[0]->get('label')->errors);
    }
}

final class InvoiceType implements FormTypeInterface
{
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('customer', CustomerType::class, [
                'label' => 'Customer',
            ])
            ->add('items', CollectionType::class, [
                'label' => 'Items',
                'entry_type' => InvoiceLineType::class,
                'entry_options' => [],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save invoice',
            ]);
    }
}

final class CustomerType implements FormTypeInterface
{
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [new Required()],
            ])
            ->add('reference', TextType::class);
    }
}

final class InvoiceLineType implements FormTypeInterface
{
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('label', TextType::class, [
                'constraints' => [new Required()],
            ])
            ->add('quantity', NumberType::class, [
                'constraints' => [new Required()],
            ]);
    }
}
