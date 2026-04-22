<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests\Fixtures;

use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\TextType;
use Iriven\Fluxon\Domain\Form\FormBuilder;

final class AddressType implements FormTypeInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('street', TextType::class, ['label' => 'Street'])
            ->add('city', TextType::class, ['label' => 'City']);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
    }
}
