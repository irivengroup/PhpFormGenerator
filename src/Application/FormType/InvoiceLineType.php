<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\FormType;

use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\FloatType;
use Iriven\Fluxon\Domain\Field\IntegerType;
use Iriven\Fluxon\Domain\Field\TextType;
use Iriven\Fluxon\Domain\Form\FormBuilder;

final class InvoiceLineType implements FormTypeInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('label', TextType::class, ['constraints' => [new Required()]])
            ->add('quantity', IntegerType::class, ['constraints' => [new Required()]])
            ->add('price', FloatType::class, ['constraints' => [new Required()]]);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }
}
