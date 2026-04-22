<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\ValueObject;

final class UploadedFile
{
    public function __construct(
        public readonly string $clientName,
        public readonly string $mimeType,
        public readonly int $size,
        public readonly string $tmpPath,
        public readonly int $error = 0,
    ) {
    }

    public function isValid(): bool
    {
        return $this->error === 0 && $this->tmpPath !== '';
    }

    public function extension(): string
    {
        $position = strrpos($this->clientName, '.');

        return $position === false ? '' : strtolower(substr($this->clientName, $position + 1));
    }
}
