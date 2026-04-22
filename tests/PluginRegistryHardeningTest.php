<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use InvalidArgumentException;
use Iriven\Fluxon\Infrastructure\Registry\InMemoryFieldTypeRegistry;
use Iriven\Fluxon\Infrastructure\Registry\InMemoryFormTypeRegistry;
use PHPUnit\Framework\TestCase;

final class PluginRegistryHardeningTest extends TestCase
{
    public function testEmptyFieldAliasIsRejected(): void
    {
        $registry = new InMemoryFieldTypeRegistry();

        $this->expectException(InvalidArgumentException::class);
        $registry->register('   ', 'App\\Field\\SlugType');
    }

    public function testEmptyFormAliasIsRejected(): void
    {
        $registry = new InMemoryFormTypeRegistry();

        $this->expectException(InvalidArgumentException::class);
        $registry->register('', 'App\\Form\\NewsletterType');
    }

    public function testCollisionCanBeRejectedWhenOverrideDisabled(): void
    {
        $registry = new InMemoryFieldTypeRegistry([], false);
        $registry->register('slug', 'App\\Field\\SlugType');

        $this->expectException(InvalidArgumentException::class);
        $registry->register('slug', 'App\\Field\\OtherSlugType');
    }

    public function testCollisionOverridesByDefault(): void
    {
        $registry = new InMemoryFormTypeRegistry();
        $registry->register('newsletter', 'App\\Form\\NewsletterType');
        $registry->register('newsletter', 'App\\Form\\OtherNewsletterType');

        self::assertSame('App\\Form\\OtherNewsletterType', $registry->resolve('newsletter'));
    }
}
