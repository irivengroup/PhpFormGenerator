<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Events;

use Iriven\Fluxon\Domain\Form\Form;

/** @api */
final class FormBuildEvent
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        public Form $form,
        public array $context = [],
    ) {
    }
}
