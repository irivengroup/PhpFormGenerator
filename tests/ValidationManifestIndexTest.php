<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use PHPUnit\Framework\TestCase;

final class ValidationManifestIndexTest extends TestCase
{
    public function testValidationManifestIndexDoesNotContainDuplicatedDirectoryPrefix(): void
    {
        $content = file_get_contents(__DIR__ . '/../VALIDATION_MANIFEST.md');
        self::assertIsString($content);
        self::assertStringNotContainsString('validations.d/validations.d/', $content);
    }
}
