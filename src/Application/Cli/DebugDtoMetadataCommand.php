<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Cli;

use Iriven\Fluxon\Application\Generation\DtoAttributeReader;
use Iriven\Fluxon\Domain\Attribute\FormField;
use Iriven\Fluxon\Domain\Attribute\FormIgnore;

/** @api */
final class DebugDtoMetadataCommand implements CliCommandInterface
{
    public function name(): string
    {
        return 'debug:dto-metadata';
    }

    /**
     * @param array<int, string> $args
     */
    public function run(array $args = []): string
    {
        $dto = new class {
            #[FormField(type: 'EmailType', required: true, label: 'Email')]
            public string $email = 'john@example.com';

            #[FormIgnore]
            public string $internal = 'secret';
        };

        return json_encode((new DtoAttributeReader())->read($dto), JSON_PRETTY_PRINT) ?: '{}';
    }
}
