<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Infrastructure\Http;

use Iriven\PhpFormGenerator\Domain\Contract\RequestInterface;

final class NativeRequest implements RequestInterface
{
    public function all(): array
    {
        return strtoupper($this->method()) === 'GET' ? $_GET : $_POST;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function files(): array
    {
        return $_FILES;
    }
}
