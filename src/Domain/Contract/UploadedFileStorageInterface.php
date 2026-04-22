<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

use Iriven\Fluxon\Domain\ValueObject\UploadedFile;

interface UploadedFileStorageInterface
{
    public function store(UploadedFile $file, ?string $directory = null, ?string $targetName = null): string;
}
