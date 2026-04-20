<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Application\Frontend\HeadlessSchemaBuilder;
use Iriven\PhpFormGenerator\Application\Schema\SchemaMigrator;
use Iriven\PhpFormGenerator\Application\Schema\SchemaVersionManager;
use Iriven\PhpFormGenerator\Domain\Contract\SchemaExporterInterface;
use Iriven\PhpFormGenerator\Domain\Form\Form;

final class FormSchemaManager
{
    public function __construct(
        private readonly SchemaExporterInterface $exporter,
        private readonly ?FormHookKernel $hookKernel = null,
        private readonly ?HeadlessSchemaBuilder $headlessSchemaBuilder = null,
        private readonly ?SchemaVersionManager $schemaVersionManager = null,
        private readonly ?SchemaMigrator $schemaMigrator = null,
    ) {
    }

    /**
     * Legacy-compatible export: keeps historical top-level shape stable.
     *
     * @return array<string, mixed>
     */
    public function export(Form $form, ?FormRuntimeContext $runtimeContext = null): array
    {
        $payload = ['runtime' => $runtimeContext];
        $this->hookKernel?->dispatch('before_export', $form, $payload);
        $this->hookKernel?->dispatch('before_schema_export', $form, $payload);

        $schema = $this->exporter->export($form);

        if ($runtimeContext instanceof FormRuntimeContext) {
            $schema['runtime'] = [
                'theme' => $runtimeContext->theme(),
                'renderer' => $runtimeContext->renderer(),
                'metadata' => $runtimeContext->metadata(),
                'payload' => [
                    'theme' => $runtimeContext->payload()->theme(),
                    'renderer' => $runtimeContext->payload()->renderer(),
                    'metadata' => $runtimeContext->payload()->metadata(),
                ],
            ];
            $schema['ui'] = [
                'theme' => $runtimeContext->theme(),
                'variant' => $runtimeContext->payload()->metadataValue('variant'),
            ];
        }

        $afterPayload = [
            'schema' => $schema,
            'runtime' => $runtimeContext,
        ];
        $this->hookKernel?->dispatch('after_schema_export', $form, $afterPayload);
        $this->hookKernel?->dispatch('after_export', $form, $afterPayload);

        return $schema;
    }

    /**
     * Headless export: versioned schema contract for frontend consumers.
     *
     * @return array<string, mixed>
     */
    public function exportHeadless(Form $form, ?FormRuntimeContext $runtimeContext = null): array
    {
        $schema = $this->export($form, $runtimeContext);

        $builder = $this->headlessSchemaBuilder ?? new HeadlessSchemaBuilder();

        return ($this->schemaVersionManager ?? new SchemaVersionManager())->stamp($builder->build($form, $schema));
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    public function migrateSchema(array $schema, string $targetVersion): array
    {
        return ($this->schemaMigrator ?? new SchemaMigrator())->migrate($schema, $targetVersion);
    }
}
