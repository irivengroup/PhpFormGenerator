<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

use Iriven\PhpFormGenerator\Domain\Form\FormView;

interface RendererInterface
{
    public function renderForm(FormView $view): string;
}
