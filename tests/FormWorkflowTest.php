<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Domain\Constraint\Email;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Presentation\Html\HtmlRenderer;
use PHPUnit\Framework\TestCase;

final class FormWorkflowTest extends TestCase
{
    public function test_form_submission_and_rendering(): void
    {
        $form = (new FormFactory())
            ->createBuilder('contact', ['method' => 'POST'])
            ->addFieldset(['legend' => 'Contact'])
            ->add('name', TextType::class, ['constraints' => [new Required()]])
            ->add('email', EmailType::class, ['constraints' => [new Required(), new Email()]])
            ->endFieldset()
            ->getForm();

        $form->handleRequest(new ArrayRequest('POST', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid());

        $html = (new HtmlRenderer())->render($form);
        self::assertStringContainsString('<fieldset>', $html);
        self::assertStringContainsString('alice@example.com', $html);
    }
}
