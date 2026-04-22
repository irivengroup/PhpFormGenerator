<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests\Fixtures;

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
            ->add('quantity', IntegerType::class)
            ->add('price', FloatType::class);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
    }
}
