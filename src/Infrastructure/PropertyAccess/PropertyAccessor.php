<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\PropertyAccess;

final class PropertyAccessor
{
    private PropertyReader $reader;
    private PropertyWriter $writer;

    public function __construct()
    {
        $this->reader = new PropertyReader();
        $this->writer = new PropertyWriter();
    }

    public function getValue(mixed $source, string $path, mixed $default = null): mixed
    {
        return $this->reader->getValue($source, $path, $default);
    }

    public function setValue(mixed &$target, string $path, mixed $value): void
    {
        $this->writer->setValue($target, $path, $value);
    }
}
