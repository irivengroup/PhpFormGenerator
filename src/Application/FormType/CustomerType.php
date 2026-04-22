<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\FormType;

use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\EmailType;
use Iriven\Fluxon\Domain\Field\TextType;
use Iriven\Fluxon\Domain\Form\FormBuilder;

final class CustomerType implements FormTypeInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('name', TextType::class, ['constraints' => [new Required()]])
            ->add('email', EmailType::class, ['constraints' => [new Required()]]);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }
}
