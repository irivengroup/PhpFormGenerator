<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Cli;

/**
 * @api
 */
final class MakePluginCommand implements CliCommandInterface
{
    public function name(): string
    {
        return 'make:plugin';
    }

    /**
     * @param array<int, string> $args
     */
    public function run(array $args = []): string
    {
        $name = trim($args[0] ?? '');
        $name = $name !== '' ? $name : 'GeneratedPlugin';

        return <<<TXT
<?php

declare(strict_types=1);

use Iriven\Fluxon\Domain\Contract\PluginInterface;
use Iriven\Fluxon\Domain\Contract\FieldTypeRegistryInterface;
use Iriven\Fluxon\Domain\Contract\FormTypeRegistryInterface;
use Iriven\Fluxon\Infrastructure\Extension\ExtensionRegistry;

final class {$name} implements PluginInterface
{
    public function registerFieldTypes(FieldTypeRegistryInterface \$registry): void
    {
    }

    public function registerFormTypes(FormTypeRegistryInterface \$registry): void
    {
    }

    public function registerExtensions(ExtensionRegistry \$registry): void
    {
    }
}
TXT;
    }
}
