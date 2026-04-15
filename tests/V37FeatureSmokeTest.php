<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use BackedEnum;
use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Domain\Constraint\GroupedConstraint;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Constraint\When;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;
use Iriven\PhpFormGenerator\Domain\Transformer\EnumTransformer;
use Iriven\PhpFormGenerator\Infrastructure\Extension\ExtensionRegistry;
use Iriven\PhpFormGenerator\Infrastructure\Extension\TrimTextFieldExtension;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Infrastructure\Security\NullCsrfManager;
use PHPUnit\Framework\TestCase;

enum LeadStatus: string
{
    case New = 'new';
    case Won = 'won';
}

final class V37FeatureSmokeTest extends TestCase
{
    public function testEnumTransformerWorks(): void
    {
        $transformer = new EnumTransformer(LeadStatus::class);

        self::assertSame('new', $transformer->transform(LeadStatus::New));
        self::assertSame(LeadStatus::Won, $transformer->reverseTransform('won'));
    }

    public function testFieldExtensionRegistryCanTrimTextFields(): void
    {
        $registry = new ExtensionRegistry();
        $registry->addFieldTypeExtension(new TrimTextFieldExtension());

        $form = (new FormFactory(new NullCsrfManager(), extensionRegistry: $registry))
            ->createBuilder('lead')
            ->add('name', TextType::class, [
                'constraints' => [new GroupedConstraint(new Required(), ['Default'])],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest(new ArrayRequest('POST', [
            'lead' => [
                'name' => '  Alice  ',
            ],
        ]));

        $data = $form->getData();
        self::assertSame('Alice', $data['name'] ?? null);
    }

    public function testConditionalConstraintCanTrigger(): void
    {
        $constraint = new When(
            static fn (mixed $value, array $context): bool => ($context['data']['contactByPhone'] ?? false) === true,
            [new Required('Phone is required.')],
        );

        self::assertSame(['Phone is required.'], $constraint->validate('', ['data' => ['contactByPhone' => true]]));
        self::assertSame([], $constraint->validate('', ['data' => ['contactByPhone' => false]]));
    }
}
