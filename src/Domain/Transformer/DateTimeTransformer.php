<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Transformer;

use DateTimeImmutable;
use DateTimeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\DataTransformerInterface;

final class DateTimeTransformer implements DataTransformerInterface
{
    public function __construct(private readonly string $format = 'Y-m-d\TH:i')
    {
    }

    public function transform(mixed $value): mixed
    {
        if (!$value instanceof DateTimeInterface) {
            return $value;
        }

        return $value->format($this->format);
    }

    public function reverseTransform(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat($this->format, (string) $value);

        if ($date instanceof DateTimeImmutable) {
            return $date;
        }

        return new DateTimeImmutable((string) $value);
    }
}
