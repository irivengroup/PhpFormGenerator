<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Runtime;

/** @api */
final class InMemoryTransport implements TransportInterface
{
    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function send(array $payload): array
    {
        return $payload;
    }
}
