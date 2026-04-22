<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Runtime;

use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Application\FormSchemaManager;
use Iriven\PhpFormGenerator\Application\Frontend\FrontendSdk;
use Iriven\PhpFormGenerator\Application\Headless\HeadlessFormProcessor;
use Iriven\PhpFormGenerator\Application\PublicApi\UnifiedSchemaExporter;
use Iriven\PhpFormGenerator\Infrastructure\Schema\ArraySchemaExporter;

/** @api */
final class FormRuntimeEngine
{
    public function __construct(
        private readonly ?FormFactory $factory = null,
        private readonly ?HeadlessFormProcessor $headless = null,
        private readonly ?UnifiedSchemaExporter $exporter = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function build(string $name, ?ExecutionContext $context = null): array
    {
        $form = ($this->factory ?? new FormFactory())->createBuilder($name)->getForm();
        return ['form' => $form->getName(), 'status' => 'built', 'context' => $this->ctx($context)];
    }

    /**
     * @return array<string, mixed>
     */
    public function schema(string $name, ?ExecutionContext $context = null): array
    {
        $form = ($this->factory ?? new FormFactory())->createBuilder($name)->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()));
        $exporter = $this->exporter ?? new UnifiedSchemaExporter($sdk);
        return ['name' => $form->getName(), 'schema' => $exporter->export($form), 'context' => $this->ctx($context)];
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function submit(string $name, array $payload, ?ExecutionContext $context = null): array
    {
        $form = ($this->factory ?? new FormFactory())->createBuilder($name)->getForm();
        $processor = $this->headless ?? new HeadlessFormProcessor();
        $r = $processor->submit($form, $payload);
        $r['context'] = $this->ctx($context);
        return $r;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function validate(string $name, array $payload, ?ExecutionContext $context = null): array
    {
        $form = ($this->factory ?? new FormFactory())->createBuilder($name)->getForm();
        $processor = $this->headless ?? new HeadlessFormProcessor();
        $r = $processor->validate($form, $payload);
        $r['context'] = $this->ctx($context);
        return $r;
    }

    /**
     * @return array<string, mixed>
     */
    private function ctx(?ExecutionContext $context): array
    {
        if ($context === null) {
            return [];
        }
        return ['request_id' => $context->requestId(), 'timestamp' => $context->timestamp(), 'source' => $context->source()];
    }
}
