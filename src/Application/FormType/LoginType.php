<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\FormType;

use Iriven\PhpFormGenerator\Domain\Constraint\Length;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\OptionsResolverInterface;
use Iriven\PhpFormGenerator\Domain\Field\CheckboxType;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\PasswordType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;

final class LoginType implements FormTypeInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [new Required(), new Length(min: 5, max: 180)],
                'autocomplete' => 'username',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'constraints' => [new Required(), new Length(min: 6, max: 255)],
                'autocomplete' => 'current-password',
            ])
            ->add('remember_me', CheckboxType::class, [
                'label' => 'Remember me',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sign in',
            ]);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => true,
        ]);
    }
}
