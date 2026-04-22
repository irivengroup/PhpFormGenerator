<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Transformer;

use DateTimeImmutable;
use DateTimeInterface;
use Iriven\Fluxon\Domain\Contract\DataTransformerInterface;

final class DateTimeTransformer implements DataTransformerInterface
{
    public function __construct(private readonly string $format = 'Y-m-d\TH:i')
    {
    }

    public function transform(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format($this->format);
        }

        return $value;
    }

    public function reverseTransform(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        try {
            return new DateTimeImmutable((string) $value);
        } catch (\Exception) {
            return null;
        }
    }
}
