<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Domain\Form\FormView;
use Iriven\Fluxon\Presentation\Html\HtmlRenderer;
use PHPUnit\Framework\TestCase;

final class HtmlAccessibilityRendererTest extends TestCase
{
    public function testRenderedFieldIncludesAccessibilityAttributes(): void
    {
        $view = new FormView(
            'email',
            'contact[email]',
            'form_email',
            'Iriven\\Fluxon\\Domain\\Field\\EmailType',
            '',
            [
                'label' => 'Email',
                'type_class' => 'Iriven\\Fluxon\\Domain\\Field\\EmailType',
                'required' => true,
                'help' => 'Enter your email address',
            ],
            [],
            ['Invalid email'],
        );

        $html = (new HtmlRenderer())->renderRow($view);

        self::assertStringContainsString('aria-invalid="true"', $html);
        self::assertStringContainsString('aria-describedby="form_email_help form_email_errors"', $html);
        self::assertStringContainsString('id="form_email_help"', $html);
        self::assertStringContainsString('id="form_email_errors"', $html);
        self::assertStringContainsString('role="alert"', $html);
    }
}
