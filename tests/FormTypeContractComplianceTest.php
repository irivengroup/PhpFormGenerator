<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormType\ContactType;
use PHPUnit\Framework\TestCase;

final class FormTypeContractComplianceTest extends TestCase
{
    public function testContactTypeImplementsConfigureOptionsMethod(): void
    {
        self::assertTrue(method_exists(ContactType::class, 'configureOptions'));
    }
}
