<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\FormType;

use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\CollectionType;
use Iriven\Fluxon\Domain\Field\DatetimeType;
use Iriven\Fluxon\Domain\Field\SubmitType;
use Iriven\Fluxon\Domain\Form\FormBuilder;

final class InvoiceType implements FormTypeInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->addFieldset([
                'legend' => 'Invoice',
                'description' => 'Invoice header and line items.',
            ])
            ->add('customer', CustomerType::class, ['label' => 'Customer'])
            ->add('issuedAt', DatetimeType::class, ['constraints' => [new Required()]])
            ->add('items', CollectionType::class, [
                'entry_type' => InvoiceLineType::class,
                'entry_options' => [],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ])
            ->endFieldset()
            ->add('submit', SubmitType::class, ['label' => 'Save invoice']);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => true,
        ]);
    }
}
