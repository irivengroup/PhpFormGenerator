<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Infrastructure\Event;

use Iriven\Fluxon\Domain\Contract\EventDispatcherInterface;
use Iriven\Fluxon\Domain\Contract\EventSubscriberInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    /** @var array<string, array<int, callable>> */
    private array $listeners = [];

    public function addListener(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName] ??= [];
        $this->listeners[$eventName][] = $listener;
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
        foreach ($subscriber::getSubscribedEvents() as $eventName => $method) {
            $this->addListener($eventName, [$subscriber, $method]);
        }
    }

    public function dispatch(string $eventName, object $event): object
    {
        foreach ($this->listeners[$eventName] ?? [] as $listener) {
            $listener($event);
        }

        return $event;
    }
}
