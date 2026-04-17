<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Form\Form;
use Iriven\PhpFormGenerator\Presentation\Html\HtmlRendererFactory;

final class FormRenderManager
{
    public function __construct(
        private readonly HtmlRendererFactory $rendererFactory,
        private readonly ?FormHookKernel $hookKernel = null,
        private readonly ?FormRuntimePipeline $pipeline = null,
    ) {
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function render(Form $form, ?string $themeAlias = null, array $metadata = []): string
    {
        $renderer = $this->rendererFactory->create($themeAlias);
        $view = $form->createView();
        $context = new FormRuntimeContext($form, $themeAlias, $renderer::class, $metadata);

        $payload = [
            'runtime' => $context,
            'view' => $view,
        ];

        $this->pipeline?->dispatch('before_render', $form, $payload);
        $this->hookKernel?->dispatch('before_render', $form, $payload);

        $html = $renderer->renderForm($view);

        $afterPayload = $payload + ['html' => $html];
        $this->hookKernel?->dispatch('after_render', $form, $afterPayload);
        $this->pipeline?->dispatch('after_render', $form, $afterPayload);

        return $html;
    }
}
