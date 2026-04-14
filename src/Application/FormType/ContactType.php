<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\FormType;

use Iriven\PhpFormGenerator\Domain\Constraint\Email;
use Iriven\PhpFormGenerator\Domain\Constraint\Length;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\OptionsResolverInterface;
use Iriven\PhpFormGenerator\Domain\Field\CaptchaType;
use Iriven\PhpFormGenerator\Domain\Field\CountryType;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\PhoneType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Field\TextareaType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;

final class ContactType implements FormTypeInterface
{
    /** @param array<string, mixed> $options */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->addFieldset([
                'legend' => 'Contact information',
                'description' => 'Basic information required to identify the sender.',
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'constraints' => [new Required(), new Length(min: 2, max: 120)],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [new Required(), new Email()],
            ])
            ->add('phone', PhoneType::class, [
                'label' => 'Phone',
                'required' => false,
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'required' => false,
            ])
            ->endFieldset()
            ->addFieldset([
                'legend' => 'Message',
                'description' => 'Describe your request.',
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'constraints' => [new Required(), new Length(min: 3, max: 180)],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [new Required(), new Length(min: 10, max: 5000)],
                'attr' => ['rows' => 6],
            ])
            ->endFieldset()
            ->add('captcha', CaptchaType::class, [
                'label' => 'Security code',
                'min_length' => 5,
                'max_length' => 8,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Send message']);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => true,
        ]);
    }
}
