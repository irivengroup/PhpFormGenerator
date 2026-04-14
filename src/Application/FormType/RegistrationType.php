<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\FormType;

use Iriven\PhpFormGenerator\Domain\Constraint\Callback;
use Iriven\PhpFormGenerator\Domain\Constraint\Email;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\OptionsResolverInterface;
use Iriven\PhpFormGenerator\Domain\Field\CaptchaType;
use Iriven\PhpFormGenerator\Domain\Field\CheckboxType;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\PasswordType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;

final class RegistrationType implements FormTypeInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('email', EmailType::class, ['constraints' => [new Required(), new Email()]])
            ->add('password', PasswordType::class, ['constraints' => [new Required()]])
            ->add('confirmPassword', PasswordType::class, ['constraints' => [new Required()]])
            ->add('acceptTerms', CheckboxType::class, [
                'constraints' => [
                    new Callback(static function (mixed $value): array {
                        return $value === true ? [] : ['You must accept the terms.'];
                    }),
                ],
            ])
            ->add('captcha', CaptchaType::class, [
                'min_length' => 5,
                'max_length' => 8,
            ])
            ->addFormConstraint(new Callback(static function (mixed $data): array {
                if (!is_array($data)) {
                    return ['Invalid registration payload.'];
                }

                return ($data['password'] ?? null) === ($data['confirmPassword'] ?? null)
                    ? []
                    : ['Passwords do not match.'];
            }))
            ->add('submit', SubmitType::class, ['label' => 'Register']);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => true,
        ]);
    }
}
