<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Http;

use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;

final class ArrayRequest implements RequestInterface
{
    public function __construct(
        private readonly string $method = 'POST',
        private readonly array $data = [],
        private readonly array $filesData = [],
    ) {
    }

    public function method(): string
    {
        return $this->method;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function files(string $key, mixed $default = null): mixed
    {
        return $this->filesData[$key] ?? $default;
    }
}
