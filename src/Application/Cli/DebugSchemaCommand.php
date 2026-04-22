<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application\Cli;

use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Domain\Form\Form;

/**
 * @api
 */
final class DebugSchemaCommand implements CliCommandInterface
{
    public function __construct(
        private readonly FormSchemaManager $schemaManager,
        private readonly Form $form,
    ) {
    }

    public function name(): string
    {
        return 'debug:schema';
    }

    /**
     * @param array<int, string> $args
     */
    public function run(array $args = []): string
    {
        $json = json_encode($this->schemaManager->exportHeadless($this->form), JSON_PRETTY_PRINT);

        return is_string($json) && $json !== '' ? $json : '{}';
    }
}
