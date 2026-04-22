<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use BackedEnum;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Domain\Constraint\GroupedConstraint;
use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Constraint\When;
use Iriven\Fluxon\Domain\Field\SubmitType;
use Iriven\Fluxon\Domain\Field\TextType;
use Iriven\Fluxon\Domain\Form\FormBuilder;
use Iriven\Fluxon\Domain\Transformer\EnumTransformer;
use Iriven\Fluxon\Infrastructure\Extension\ExtensionRegistry;
use Iriven\Fluxon\Infrastructure\Extension\TrimTextFieldExtension;
use Iriven\Fluxon\Infrastructure\Http\ArrayRequest;
use Iriven\Fluxon\Infrastructure\Security\NullCsrfManager;
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
