<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests\Fixtures\Hook;

use Iriven\PhpFormGenerator\Domain\Contract\FormHookInterface;
use Iriven\PhpFormGenerator\Domain\Form\Form;

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
