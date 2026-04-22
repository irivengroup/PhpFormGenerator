<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

interface ValidationGroupAwareInterface
{
    /**
     * @return array<int, string>
     */
    public function groups(): array;
}
