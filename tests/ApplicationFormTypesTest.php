<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormType\ContactType;
use Iriven\PhpFormGenerator\Application\FormType\InvoiceType;
use Iriven\PhpFormGenerator\Application\FormType\RegistrationType;
use PHPUnit\Framework\TestCase;

final class ApplicationFormTypesTest extends TestCase
{
    public function testApplicationFormTypesExist(): void
    {
        self::assertTrue(class_exists(ContactType::class));
        self::assertTrue(class_exists(InvoiceType::class));
        self::assertTrue(class_exists(RegistrationType::class));
    }

    public function testFactoryUsesCsrfProtectionByDefault(): void
    {
        $form = (new FormFactory())->create(ContactType::class);
        $view = $form->createView();

        self::assertTrue($view->options['csrf_protection'] === true);
    }
}
