<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

interface RequestInterface
{
    public function getMethod(): string;

    /** @return array<string, mixed> */
    public function all(): array;

    public function get(string $key, mixed $default = null): mixed;
}
