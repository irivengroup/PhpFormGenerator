<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Frontend\ValidationExporter;
use Iriven\Fluxon\Domain\Form\FieldConfig;
use PHPUnit\Framework\TestCase;

final class ValidationExporterRegressionTest extends TestCase
{
    public function testValidationExporterHandlesFieldWithoutConstraints(): void
    {
        $field = new FieldConfig('name', 'TextType', []);
        $rules = (new ValidationExporter())->export($field);

        self::assertFalse($rules['required']);
        self::assertArrayNotHasKey('type', $rules);
    }
}
