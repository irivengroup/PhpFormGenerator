<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Application\FormType;
use Iriven\Fluxon\Domain\Constraint\Length;
use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\CheckboxType;
use Iriven\Fluxon\Domain\Field\EmailType;
use Iriven\Fluxon\Domain\Field\PasswordType;
use Iriven\Fluxon\Domain\Field\SubmitType;
use Iriven\Fluxon\Domain\Form\FormBuilder;
final class LoginType implements FormTypeInterface {
    public function buildForm(FormBuilder $builder, array $options = []): void {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email', 'constraints' => [new Required(), new Length(min: 5, max: 180)], 'autocomplete' => 'username'])
            ->add('password', PasswordType::class, ['label' => 'Password', 'constraints' => [new Required(), new Length(min: 6, max: 255)], 'autocomplete' => 'current-password'])
            ->add('remember_me', CheckboxType::class, ['label' => 'Remember me', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Sign in']);
    }
    public function configureOptions(OptionsResolverInterface $resolver): void {
        $resolver->setDefaults(['method' => 'POST', 'csrf_protection' => true]);
    }
}
