<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests\Fixtures\Plugin;

use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\OptionsResolverInterface;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;

final class NewsletterType implements FormTypeInterface
{
    public function buildForm($builder, array $options = []): void
    {
        $builder
            ->add('email', EmailType::class, ['required' => true])
            ->add('submit', SubmitType::class, ['label' => 'Subscribe']);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => false,
        ]);
    }
}
