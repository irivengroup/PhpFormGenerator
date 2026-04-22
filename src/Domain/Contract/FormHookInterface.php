<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

use Iriven\Fluxon\Domain\Form\Form;

interface FormHookInterface extends HookInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function __invoke(Form $form, array $context = []): void;
}
