<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\FormType;

use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\OptionsResolverInterface;
use Iriven\PhpFormGenerator\Domain\Field\FloatType;
use Iriven\PhpFormGenerator\Domain\Field\IntegerType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;

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
