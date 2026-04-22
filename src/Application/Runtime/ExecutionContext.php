<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Application\Runtime;

/** @api */
final class ExecutionContext
{
    public function __construct(
        private readonly string $requestId,
        private readonly string $timestamp,
        private readonly string $source = 'internal',
    ) {}

    public function requestId(): string { return $this->requestId; }
    public function timestamp(): string { return $this->timestamp; }
    public function source(): string { return $this->source; }
}
