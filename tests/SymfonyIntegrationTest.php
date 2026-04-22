<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Integration\Symfony\SymfonyFormBridge;
use PHPUnit\Framework\TestCase;

final class SymfonyIntegrationTest extends TestCase
{
    public function testSymfonyBridgeCanCreateForm(): void
    {
        $form = (new SymfonyFormBridge())->create('contact');
        self::assertSame('contact', $form->getName());
    }
}
