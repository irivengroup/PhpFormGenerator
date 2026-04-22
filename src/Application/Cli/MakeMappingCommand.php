<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Application\Cli;
use Iriven\Fluxon\Application\Mapping\MappingBlueprintGenerator;
/** @api */
final class MakeMappingCommand implements CliCommandInterface
{
    public function name(): string { return 'make:mapping'; }
    /** @param array<int, string> $args */
    public function run(array $args = []): string
    {
        return (new MappingBlueprintGenerator())->generate(['email' => 'string', 'name' => 'string']);
    }
}
