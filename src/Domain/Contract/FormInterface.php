<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

use Iriven\PhpFormGenerator\Domain\Form\FormView;

interface FormInterface
{
    public function handleRequest(RequestInterface $request): void;

    public function isSubmitted(): bool;

    public function isValid(): bool;

    public function getData(): mixed;

    public function createView(): FormView;

    public function getErrors(bool $deep = true): array;
}
