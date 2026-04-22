<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

interface DataTransformerInterface
{
    public function transform(mixed $value): mixed;

    public function reverseTransform(mixed $value): mixed;
}
