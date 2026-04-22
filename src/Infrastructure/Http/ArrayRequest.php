<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Http;

use Iriven\Fluxon\Domain\Contract\RequestInterface;

final class ArrayRequest implements RequestInterface
{
    /** @param array<string, mixed> $data */
    public function __construct(private readonly string $method = 'GET', private readonly array $data = [])
    {
    }

    public function getMethod(): string
    {
        return strtoupper($this->method);
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
}
