<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\Mapping\MappingBlueprintGenerator;
use PHPUnit\Framework\TestCase;
final class MappingBlueprintGeneratorTest extends TestCase
{
    public function testBlueprintCanBeGeneratedFromSample(): void
    {
        $blueprint = (new MappingBlueprintGenerator())->generate(['email' => 'string', 'name' => 'string']);
        self::assertStringContainsString('mapping:', $blueprint);
        self::assertStringContainsString('email: email', $blueprint);
    }
}
