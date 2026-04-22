<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormGenerator;
use Iriven\Fluxon\Infrastructure\Http\ArrayRequest;
use Iriven\Fluxon\Infrastructure\Translation\ArrayTranslator;
use PHPUnit\Framework\TestCase;

final class FormValidationProcessorTranslatorTest extends TestCase
{
    public function testCsrfAndCaptchaMessagesCanUseTranslator(): void
    {
        $translator = new ArrayTranslator([
            'csrf.invalid' => 'Jeton CSRF invalide',
            'captcha.invalid' => 'Captcha invalide',
        ]);

        $form = (new FormGenerator('secure'))
            ->open(['method' => 'POST'], ['translator' => $translator])
            ->addCaptcha('captcha')
            ->getForm();

        $form->createView();
        $form->handleRequest(new ArrayRequest('POST', [
            'secure' => [
                '_token' => 'bad',
                'captcha' => 'wrongX',
            ],
        ]));

        $errors = $form->getErrors();
        self::assertContains('Jeton CSRF invalide', $errors['_form'] ?? []);
    }
}
