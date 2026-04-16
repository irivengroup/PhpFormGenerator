<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Contract\FormHookInterface;
use Iriven\PhpFormGenerator\Domain\Form\Form;
use Iriven\PhpFormGenerator\Infrastructure\Registry\InMemoryHookRegistry;

final class FormHookKernel
{
    private InMemoryHookRegistry $hooks;

    public function __construct()
    {
        $this->hooks = new InMemoryHookRegistry();
    }

    public function register(FormHookInterface $hook): self
    {
        $this->hooks->register($hook);

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function dispatch(string $name, Form $form, array $context = []): void
    {
        foreach ($this->hooks->for($name) as $hook) {
            $hook($form, $context);
        }
    }

    public function hooks(): InMemoryHookRegistry
    {
        return $this->hooks;
    }
}
