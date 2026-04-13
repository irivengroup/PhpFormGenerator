<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;
use Iriven\PhpFormGenerator\Infrastructure\Security\NullCsrfManager;

final class FormFactory
{
    public function __construct(private readonly ?CsrfManagerInterface $csrfManager = null)
    {
    }

    public function createBuilder(string $name = 'form', array $options = []): FormBuilder
    {
        return new FormBuilder($name, $options, $this->csrfManager ?? new NullCsrfManager());
    }
}
