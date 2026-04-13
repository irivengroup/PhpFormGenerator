<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

interface RequestInterface
{
    public function method(): string;

    public function input(string $key, mixed $default = null): mixed;

    public function files(string $key, mixed $default = null): mixed;
}
