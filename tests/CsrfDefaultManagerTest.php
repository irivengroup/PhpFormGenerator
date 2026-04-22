<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormGenerator;
use Iriven\Fluxon\Infrastructure\Http\ArrayRequest;
use PHPUnit\Framework\TestCase;

final class CsrfDefaultManagerTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
        }
    }

    public function testCsrfProtectionUsesSessionManagerByDefault(): void
    {
        $form = (new FormGenerator('secure'))
            ->open(['method' => 'POST'])
            ->addText('name')
            ->getForm();

        $form->createView();
        $form->handleRequest(new ArrayRequest('POST', [
            'secure' => [
                '_token' => 'bad-token',
                'name' => 'John',
            ],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertFalse($form->isValid());
        self::assertContains('Invalid CSRF token.', $form->getErrors()['_form'] ?? []);
    }
}
