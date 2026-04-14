<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\OptionsResolverInterface;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use PHPUnit\Framework\TestCase;

final class TypeResolverIntegrationTest extends TestCase
{
    public function testFactoryResolvesBuiltinApplicationFormTypesByShortClassName(): void
    {
        $factory = new FormFactory();
        $form = $factory->create('ContactType');

        self::assertSame('form', $form->getName());
        self::assertTrue(method_exists($form, 'handleRequest'));
    }

    public function testBuilderResolvesBuiltinFieldTypesByShortClassNameInsideCustomFormType(): void
    {
        $factory = new FormFactory();
        $form = $factory->create(DummyType::class);

        $form->handleRequest(new ArrayRequest('POST', [
            'form' => [
                'name' => 'Alice',
                'email' => 'alice@example.com',
                'captcha' => 'ABCDE',
            ],
        ]));

        self::assertTrue($form->isSubmitted());
    }
}

final class DummyType implements FormTypeInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilder $builder, array $options = []): void
    {
        $builder
            ->add('name', 'TextType', ['required' => true])
            ->add('email', 'EmailType', ['required' => true])
            ->add('captcha', 'CaptchaType', [
                'label' => 'Security code',
                'data' => 'ABCDE',
                'min_length' => 5,
                'max_length' => 8,
            ])
            ->add('submit', 'SubmitType', ['label' => 'Send']);
    }

    public function configureOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => true,
        ]);
    }
}
