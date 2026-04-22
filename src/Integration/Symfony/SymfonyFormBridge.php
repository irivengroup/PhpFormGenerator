<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Integration\Symfony;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Domain\Form\Form;

/** @api */
final class SymfonyFormBridge
{
    public function __construct(
        private readonly ?FormFactory $factory = null,
    ) {}

    public function create(string $name): Form
    {
        return ($this->factory ?? new FormFactory())->createBuilder($name)->getForm();
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function submit(Form $form, array $payload): array
    {
        return ['form' => $form->getName(), 'payload' => $payload, 'integration' => 'symfony'];
    }
}
