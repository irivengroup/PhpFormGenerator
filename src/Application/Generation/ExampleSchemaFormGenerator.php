<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Generation;

/** @api */
final class ExampleSchemaFormGenerator
{
    public function __construct(
        private readonly DtoFormGuesser $guesser = new DtoFormGuesser(),
    ) {
    }

    /**
     * @param object|array<string, mixed> $sample
     * @return array{fields: array<string, array{type: string, required?: bool, label?: string}>}
     */
    public function generate(object|array $sample): array
    {
        $fields = $this->normalizeGuessedFields($this->guesser->guess($sample));
        ksort($fields);

        return ['fields' => $fields];
    }

    /**
     * @param array<string, mixed> $guessed
     * @return array<string, array{type: string, required?: bool, label?: string}>
     */
    private function normalizeGuessedFields(array $guessed): array
    {
        $fields = [];

        foreach ($guessed as $name => $definition) {
            $normalized = $this->normalizeDefinition($definition);
            if ($normalized === null) {
                continue;
            }

            $fields[(string) $name] = $normalized;
        }

        return $fields;
    }

    /**
     * @return array{type: string, required?: bool, label?: string}|null
     */
    private function normalizeDefinition(mixed $definition): ?array
    {
        if ($this->isSimpleDefinition($definition)) {
            return $this->normalizeSimpleDefinition($definition);
        }

        if (!$this->isRichDefinition($definition)) {
            return null;
        }

        return $this->normalizeRichDefinition($definition);
    }

    private function isSimpleDefinition(mixed $definition): bool
    {
        return is_string($definition);
    }

    /**
     * @param mixed $definition
     * @return array{type: string}
     */
    private function normalizeSimpleDefinition(mixed $definition): array
    {
        return ['type' => (string) $definition];
    }

    private function isRichDefinition(mixed $definition): bool
    {
        return is_array($definition) && isset($definition['type']);
    }

    /**
     * @param array<string, mixed> $definition
     * @return array{type: string, required?: bool, label?: string}
     */
    private function normalizeRichDefinition(array $definition): array
    {
        $field = ['type' => (string) $definition['type']];
        $field = $this->withRequiredFlag($field, $definition);
        $field = $this->withLabel($field, $definition);

        return $field;
    }

    /**
     * @param array{type: string, required?: bool, label?: string} $field
     * @param array<string, mixed> $definition
     * @return array{type: string, required?: bool, label?: string}
     */
    private function withRequiredFlag(array $field, array $definition): array
    {
        if (isset($definition['required'])) {
            $field['required'] = true;
        }

        return $field;
    }

    /**
     * @param array{type: string, required?: bool, label?: string} $field
     * @param array<string, mixed> $definition
     * @return array{type: string, required?: bool, label?: string}
     */
    private function withLabel(array $field, array $definition): array
    {
        if (array_key_exists('label', $definition) && $definition['label'] !== null) {
            $field['label'] = (string) $definition['label'];
        }

        return $field;
    }
}
