<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Integration\Laravel;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Domain\Form\Form;

/** @api */
final class LaravelFormBridge
{
    public function __construct(
        private readonly ?FormFactory $factory = null,
    ) {}

    public function make(string $name): Form
    {
        return ($this->factory ?? new FormFactory())->createBuilder($name)->getForm();
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function validate(Form $form, array $payload): array
    {
        return ['form' => $form->getName(), 'payload' => $payload, 'integration' => 'laravel'];
    }
}
