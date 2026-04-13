<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\Type\ContactType;
use Iriven\PhpFormGenerator\Domain\Constraint\Email;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Infrastructure\Mapping\ArrayDataMapper;
use Iriven\PhpFormGenerator\Infrastructure\Security\NullCsrfManager;
use Iriven\PhpFormGenerator\Presentation\Html\HtmlRenderer;
use Iriven\PhpFormGenerator\Presentation\Html\Theme\DefaultTheme;
use PHPUnit\Framework\TestCase;

final class FormFactoryTest extends TestCase
{
    public function testBuilderFormCanSubmitAndValidate(): void
    {
        $factory = new FormFactory(new ArrayDataMapper(), new NullCsrfManager());
        $form = $factory->createBuilder()
            ->add('name', TextType::class, [
                'constraints' => [new Required()],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new Required(), new Email()],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest(new ArrayRequest([
            'name' => 'Alice',
            'email' => 'alice@example.test',
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid());
        self::assertSame('Alice', $form->getData()['name']);
    }

    public function testInvalidEmailProducesError(): void
    {
        $factory = new FormFactory(new ArrayDataMapper(), new NullCsrfManager());
        $form = $factory->createBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [new Required(), new Email()],
            ])
            ->getForm();

        $form->handleRequest(new ArrayRequest([
            'email' => 'not-an-email',
        ]));

        self::assertFalse($form->isValid());
        self::assertNotEmpty($form->getErrors());
    }

    public function testTypedFormCanRenderHtml(): void
    {
        $factory = new FormFactory(new ArrayDataMapper(), new NullCsrfManager());
        $form = $factory->create(ContactType::class);

        $renderer = new HtmlRenderer(new DefaultTheme());
        $html = $renderer->renderForm($form->createView());

        self::assertStringContainsString('<form', $html);
        self::assertStringContainsString('name="email"', $html);
        self::assertStringContainsString('Pays', $html);
    }
}
