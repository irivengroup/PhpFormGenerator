<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests\Fixtures\Hook;

use Iriven\Fluxon\Domain\Contract\FormHookInterface;
use Iriven\Fluxon\Domain\Form\Form;

final class InvalidateOnPreSubmitHook implements FormHookInterface
{
    public static function getName(): string
    {
        return 'pre_submit';
    }

    public function __invoke(Form $form, array $context = []): void
    {
        $form->appendError('_form', 'Pre-submit hook reached.');
        $form->setValid(false);
    }
}
