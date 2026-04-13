<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Contract;

interface RequestInterface
{
    public function all(): array;

    public function get(string $key, mixed $default = null): mixed;

    public function method(): string;

    public function files(): array;
}
