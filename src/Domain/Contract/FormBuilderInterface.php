<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

interface FormBuilderInterface
{
    public function add(string $name, string $typeClass, array $options = []): self;

    public function setData(mixed $data): self;

    public function setOption(string $name, mixed $value): self;

    public function getForm(): FormInterface;
}
