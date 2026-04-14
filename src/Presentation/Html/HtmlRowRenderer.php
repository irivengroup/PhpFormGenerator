<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Presentation\Html;

use Iriven\PhpFormGenerator\Domain\Form\FormView;

final class HtmlRowRenderer
{
    public function __construct(private readonly HtmlRenderer $renderer)
    {
    }

    public function render(FormView $view): string
    {
        return $this->renderer->renderRow($view);
    }
}
