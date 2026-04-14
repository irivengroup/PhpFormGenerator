<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\FormType;

use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Constraint\Email;
use Iriven\PhpFormGenerator\Domain\Constraint\Length;
use Iriven\PhpFormGenerator\Domain\Contract\OptionsResolverInterface;

final class ContactType implements FormTypeInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->addFieldset([
                'legend' => 'Contact information',
                'description' => 'Basic information required to send your message.',
            ])
            ->addText('name', [
                'label' => 'Name',
                'constraints' => [
                    new Required('Name is required.'),
                    new Length(min: 2, max: 120),
                ],
                'attr' => [
                    'autocomplete' => 'name',
                ],
            ])
            ->addEmail('email', [
                'label' => 'Email',
                'constraints' => [
                    new Required('Email is required.'),
                    new Email(),
                ],
                'attr' => [
                    'autocomplete' => 'email',
                ],
            ])
            ->addPhone('phone', [
                'label' => 'Phone',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'tel',
                ],
            ])
            ->addCountries('country', [
                'label' => 'Country',
                'required' => false,
                'placeholder' => 'Select a country',
            ])
            ->endFieldset()
            ->addFieldset([
                'legend' => 'Your message',
                'description' => 'Provide the subject and details of your request.',
            ])
            ->addText('subject', [
                'label' => 'Subject',
                'constraints' => [
                    new Required('Subject is required.'),
                    new Length(min: 3, max: 180),
                ],
            ])
            ->addTextarea('message', [
                'label' => 'Message',
                'constraints' => [
                    new Required('Message is required.'),
                    new Length(min: 10, max: 5000),
                ],
                'attr' => [
                    'rows' => 6,
                ],
            ])
            ->endFieldset()
            ->addCaptcha('captcha', [
                'label' => 'Security code',
                'length' => 6,
            ])
            ->addSubmit('submit', [
                'label' => 'Send message',
            ]);
    }
public function configureOptions(OptionsResolverInterface $resolver): void
{
    $resolver->setDefaults([
        'csrf_protection' => true,
    ]);
}

}
