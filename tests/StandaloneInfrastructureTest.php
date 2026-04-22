<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Infrastructure\Event\EventDispatcher;
use Iriven\Fluxon\Infrastructure\Options\OptionsResolver;
use PHPUnit\Framework\TestCase;

final class StandaloneInfrastructureTest extends TestCase
{
    public function testEventDispatcherDispatchesListener(): void
    {
        $dispatcher = new EventDispatcher();
        $called = false;

        $dispatcher->addListener('demo.event', function (object $event) use (&$called): void {
            $called = $event instanceof \stdClass;
        });

        $dispatcher->dispatch('demo.event', new \stdClass());

        self::assertTrue($called);
    }

    public function testOptionsResolverResolvesDefaultsAndTypes(): void
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'method' => 'POST',
            'csrf_protection' => false,
        ]);
        $resolver->setAllowedTypes('method', 'string');
        $resolver->setAllowedTypes('csrf_protection', 'bool');

        $resolved = $resolver->resolve(['method' => 'GET']);

        self::assertSame('GET', $resolved['method']);
        self::assertFalse($resolved['csrf_protection']);
    }
}
