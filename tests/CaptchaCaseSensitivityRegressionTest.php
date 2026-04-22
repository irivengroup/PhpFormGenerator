<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Infrastructure\Security\SessionCaptchaManager;
use PHPUnit\Framework\TestCase;

final class CaptchaCaseSensitivityRegressionTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
        }
    }

    public function testGeneratedCodeAlwaysContainsUpperAndLowerCaseCharacters(): void
    {
        $manager = new SessionCaptchaManager();
        $code = $manager->generateCode('demo', 5, 8);

        self::assertMatchesRegularExpression('/[A-Z]/', $code);
        self::assertMatchesRegularExpression('/[a-z]/', $code);
    }
}
