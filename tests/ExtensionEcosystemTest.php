<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Tests;

use Iriven\PhpFormGenerator\Infrastructure\Extension\ExtensionRegistry;
use Iriven\PhpFormGenerator\Domain\Contract\ExtensionInterface;
use PHPUnit\Framework\TestCase;

final class ExtensionEcosystemTest extends TestCase
{
    public function testExtensionRegistration(): void
    {
        $registry = new ExtensionRegistry();

        $ext = new class implements ExtensionInterface {
            public function supports(string $type): bool { return $type === 'text'; }
            public function apply(array $options): array { return $options; }
        };

        $registry->addFieldExtension($ext);

        self::assertCount(1, $registry->for('text'));
    }
}
