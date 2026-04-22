<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Application\FormType;
use Iriven\Fluxon\Domain\Constraint\Length;
use Iriven\Fluxon\Domain\Constraint\Required;
use Iriven\Fluxon\Domain\Contract\FormTypeInterface;
use Iriven\Fluxon\Domain\Contract\OptionsResolverInterface;
use Iriven\Fluxon\Domain\Field\HiddenType;
use Iriven\Fluxon\Domain\Field\PasswordType;
use Iriven\Fluxon\Domain\Field\SubmitType;
use Iriven\Fluxon\Domain\Form\FormBuilder;
final class ResetPasswordType implements FormTypeInterface {
    public function buildForm(FormBuilder $builder, array $options = []): void {
        $builder
            ->add('token', HiddenType::class)
            ->add('password', PasswordType::class, ['label' => 'New password', 'constraints' => [new Required(), new Length(min: 6, max: 255)], 'autocomplete' => 'new-password'])
            ->add('password_confirmation', PasswordType::class, ['label' => 'Confirm password', 'constraints' => [new Required(), new Length(min: 6, max: 255)], 'autocomplete' => 'new-password'])
            ->add('submit', SubmitType::class, ['label' => 'Reset password']);
    }
    public function configureOptions(OptionsResolverInterface $resolver): void {
        $resolver->setDefaults(['method' => 'POST', 'csrf_protection' => true]);
    }
}
