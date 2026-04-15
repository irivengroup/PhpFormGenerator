<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormGenerator;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use PHPUnit\Framework\TestCase;

final class CaptchaTypeTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
        }
    }

    public function testCaptchaRendersAndValidatesCaseSensitively(): void
    {
        $form = (new FormGenerator('secure'))
            ->open(['method' => 'POST'])
            ->addCaptcha('captcha', ['min_length' => 5, 'max_length' => 8])
            ->getForm();

        $view = $form->createView();
        $csrfToken = null;
        foreach ($view->children as $child) {
            if ($child->name === '_token') {
                $csrfToken = is_string($child->value) ? $child->value : null;
            }
        }
        /** @var mixed $captchaView */
        $captchaView = null;
        foreach ($view->children as $child) {
            if ($child->name === 'captcha') {
                $captchaView = $child;
                break;
            }
        }

        self::assertNotNull($captchaView);
        self::assertIsArray($captchaView->vars);
        self::assertArrayHasKey('captcha_svg', $captchaView->vars);
        self::assertIsString($captchaView->vars['captcha_svg']);

        $expected = $_SESSION['_pfg_captcha']['secure.captcha'] ?? null;
        self::assertIsString($expected);
        if (!is_string($expected)) {
            self::fail('Expected captcha code was not generated.');
        }

        $form->handleRequest(new ArrayRequest('POST', [
            'secure' => [
                '_token' => $csrfToken,
                'captcha' => strtolower($expected),
            ],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertFalse($form->isValid());

        $form = (new FormGenerator('secure'))
            ->open(['method' => 'POST'])
            ->addCaptcha('captcha', ['min_length' => 5, 'max_length' => 8])
            ->getForm();
        $view = $form->createView();
        $csrfToken = null;
        foreach ($view->children as $child) {
            if ($child->name === '_token') {
                $csrfToken = is_string($child->value) ? $child->value : null;
            }
        }
        $expected = $_SESSION['_pfg_captcha']['secure.captcha'] ?? null;
        self::assertIsString($expected);
        if (!is_string($expected)) {
            self::fail('Expected captcha code was not generated.');
        }

        $form->handleRequest(new ArrayRequest('POST', [
            'secure' => [
                '_token' => $csrfToken,
                'captcha' => $expected,
            ],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertTrue($form->isValid());
    }
}
