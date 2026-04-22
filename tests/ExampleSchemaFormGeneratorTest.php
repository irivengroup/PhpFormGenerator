<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Generation\ExampleSchemaFormGenerator;
use PHPUnit\Framework\TestCase;

final class ExampleSchemaFormGeneratorTest extends TestCase
{
    public function testGenerateSchemaFromSample(): void
    {
        $schema = (new ExampleSchemaFormGenerator())->generate(['email' => 'john@example.com']);

        self::assertArrayHasKey('fields', $schema);
        self::assertArrayHasKey('email', $schema['fields']);
        self::assertSame('TextType', $schema['fields']['email']['type']);
    }
}
