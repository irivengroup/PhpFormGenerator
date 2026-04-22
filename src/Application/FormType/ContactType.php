<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\FormType;

use Iriven\Fluxon\Domain\Constraint\Email;
use Iriven\Fluxon\Domain\Constraint\Length;
use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\CaptchaType;
use Iriven\Fluxon\Domain\Field\CountryType;
use Iriven\Fluxon\Domain\Field\EmailType;
use Iriven\Fluxon\Domain\Field\PhoneType;
use Iriven\Fluxon\Domain\Field\SubmitType;
use Iriven\Fluxon\Domain\Field\TextareaType;
use Iriven\Fluxon\Domain\Field\TextType;
use Iriven\Fluxon\Domain\Form\FormBuilder;

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
