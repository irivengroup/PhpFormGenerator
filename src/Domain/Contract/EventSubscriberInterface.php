<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Contract;

interface EventSubscriberInterface
{
    /** @return array<string, string> */
    public static function getSubscribedEvents(): array;
}
