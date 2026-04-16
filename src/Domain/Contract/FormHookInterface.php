<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

use Iriven\PhpFormGenerator\Domain\Form\Form;

interface FormHookInterface extends HookInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function __invoke(Form $form, array $context = []): void;
}
