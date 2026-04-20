<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Frontend;

use Iriven\PhpFormGenerator\Application\FormRuntimeContext;
use Iriven\PhpFormGenerator\Application\FormSchemaManager;
use Iriven\PhpFormGenerator\Application\Rendering\RenderProfile;
use Iriven\PhpFormGenerator\Application\Rendering\RenderProfileManager;
use Iriven\PhpFormGenerator\Domain\Form\Form;

/**
 * @api
 */
final class FrontendSdk
{
    public function __construct(
        private readonly FormSchemaManager $schemaManager,
        private readonly FrontendSdkConfig $config = new FrontendSdkConfig(),
        private readonly ?FrontendSchemaRendererConfig $rendererConfig = null,
        private readonly ?RenderProfileManager $renderProfileManager = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function buildSchema(Form $form, ?FormRuntimeContext $runtimeContext = null): array
    {
        $schema = (new HeadlessSchemaBuilder(
            new UiComponentResolver(),
            new ValidationExporter(),
            $this->rendererConfig
        ))->build($form, $this->schemaManager->export($form, $runtimeContext));

        $schema += [
            'form' => [],
            'fields' => [],
            'ui' => [],
            'runtime' => [],
            'validation' => [],
        ];

        $renderProfile = new RenderProfile(
            $runtimeContext?->theme() ?? 'default',
            $runtimeContext?->payload()->metadataValue('channel', 'headless') ?? 'headless',
            ['renderer' => $runtimeContext?->renderer()]
        );

        $runtime = is_array($schema['runtime'] ?? null) ? $schema['runtime'] : [];
        $runtime['rendering'] = ($this->renderProfileManager ?? new RenderProfileManager())->export($renderProfile);
        $schema['runtime'] = $runtime;

        $schema['schema'] = ['version' => $this->config->schemaVersion()];
        $schema['sdk'] = [
            'framework' => $this->config->framework(),
            'schema_version' => $this->config->schemaVersion(),
            'defaults' => $this->config->defaults(),
        ];

        return $schema;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function buildSubmissionPayload(Form $form, array $data): array
    {
        return [
            'form' => $form->getName(),
            'payload' => $data,
            'sdk' => [
                'framework' => $this->config->framework(),
                'schema_version' => $this->config->schemaVersion(),
            ],
        ];
    }

    public function getSchemaVersion(): string
    {
        return $this->config->schemaVersion();
    }

    public function getFramework(): string
    {
        return $this->config->framework();
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function validatePayload(array $data): array
    {
        return $data;
    }
}
