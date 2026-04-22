<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\File;

use Iriven\Fluxon\Domain\Contract\UploadedFileStorageInterface;
use Iriven\Fluxon\Domain\ValueObject\UploadedFile;

final class LocalUploadedFileStorage implements UploadedFileStorageInterface
{
    public function __construct(private readonly string $baseDirectory)
    {
    }

    public function store(UploadedFile $file, ?string $directory = null, ?string $targetName = null): string
    {
        $relativeDirectory = trim((string) $directory, '/');
        $targetDirectory = rtrim($this->baseDirectory, DIRECTORY_SEPARATOR);
        if ($relativeDirectory !== '') {
            $targetDirectory .= DIRECTORY_SEPARATOR . $relativeDirectory;
        }

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        $name = $targetName ?? bin2hex(random_bytes(8)) . ($file->extension() !== '' ? '.' . $file->extension() : '');
        $path = $targetDirectory . DIRECTORY_SEPARATOR . $name;

        if (!@rename($file->tmpPath, $path)) {
            copy($file->tmpPath, $path);
        }

        return $path;
    }
}
