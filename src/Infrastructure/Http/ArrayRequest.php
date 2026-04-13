<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Http;

use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;

final class ArrayRequest implements RequestInterface
{
    public function __construct(
        private readonly array $data = [],
        private readonly string $method = 'POST',
        private readonly array $files = []
    ) {
    }

    public function all(): array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function method(): string
    {
        return strtoupper($this->method);
    }

    public function files(): array
    {
        return $this->files;
    }
}
