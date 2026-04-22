<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application\Runtime;

/** @api */
interface TransportInterface
{
    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function send(array $payload): array;
}
