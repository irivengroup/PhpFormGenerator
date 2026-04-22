<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests\Fixtures;

use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\CountryType;
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
            ->add('email', EmailType::class)
            ->add('country', CountryType::class, ['label' => 'Country']);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
    }
}
