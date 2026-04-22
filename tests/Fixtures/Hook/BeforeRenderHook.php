<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests\Fixtures\Hook;

use Iriven\Fluxon\Domain\Contract\FormHookInterface;
use Iriven\Fluxon\Domain\Form\Form;

final class BeforeRenderHook implements FormHookInterface
{
    public static function getName(): string
    {
        return 'before_render';
    }

    public function __invoke(Form $form, array $context = []): void
    {
        $form->appendError('_form', 'Before render hook reached.');
    }
}
