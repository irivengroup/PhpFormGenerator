<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use ArrayObject;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\Runtime\PrioritizedHookKernel;
use PHPUnit\Framework\TestCase;

final class PrioritizedHookKernelRegressionTest extends TestCase
{
    public function testEqualPriorityHooksRemainExecutable(): void
    {
        $captured = new ArrayObject();
        $form = (new FormFactory())->createBuilder('contact')->getForm();

        $kernel = new PrioritizedHookKernel();
        $kernel->register(new class($captured) implements \Iriven\Fluxon\Domain\Contract\FormHookInterface {
            /** @param ArrayObject<int, string> $captured */
            public function __construct(private ArrayObject $captured) {}
            public static function getName(): string { return 'before_render'; }
            public function __invoke(\Iriven\Fluxon\Domain\Form\Form $form, array $context = []): void { $this->captured->append('first'); }
        }, 50);
        $kernel->register(new class($captured) implements \Iriven\Fluxon\Domain\Contract\FormHookInterface {
            /** @param ArrayObject<int, string> $captured */
            public function __construct(private ArrayObject $captured) {}
            public static function getName(): string { return 'before_render'; }
            public function __invoke(\Iriven\Fluxon\Domain\Form\Form $form, array $context = []): void { $this->captured->append('second'); }
        }, 50);

        $kernel->dispatch('before_render', $form);

        self::assertCount(2, $captured->getArrayCopy());
    }

    public function testSwallowModeTurnsHookFailureIntoFormError(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();

        $kernel = new PrioritizedHookKernel(true);
        $kernel->register(new class implements \Iriven\Fluxon\Domain\Contract\FormHookInterface {
            public static function getName(): string { return 'before_render'; }
            public function __invoke(\Iriven\Fluxon\Domain\Form\Form $form, array $context = []): void
            {
                throw new \RuntimeException('hook failed');
            }
        });

        $kernel->dispatch('before_render', $form);

        self::assertArrayHasKey('_form', $form->getErrors());
        self::assertStringContainsString('Hook failure', $form->getErrors()['_form'][0]);
    }
}
