<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormHookKernel;
use Iriven\Fluxon\Infrastructure\Http\ArrayRequest;
use Iriven\Fluxon\Tests\Fixtures\Hook\InvalidateOnPostSubmitHook;
use Iriven\Fluxon\Tests\Fixtures\Hook\InvalidateOnPreSubmitHook;
use PHPUnit\Framework\TestCase;

final class HookLifecycleRuntimeTest extends TestCase
{
    public function testPreSubmitHookIsDispatchedDuringHandleRequest(): void
    {
        $hooks = (new FormHookKernel())->register(new InvalidateOnPreSubmitHook());
        $factory = new FormFactory(hookKernel: $hooks);
        $builder = $factory->createBuilder('demo');
        $builder->add('name', 'TextType');
        $form = $builder->getForm();

        $form->handleRequest(new ArrayRequest('POST', [
            'demo' => ['name' => 'Alice'],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertArrayHasKey('_form', $form->getErrors());
        self::assertContains('Pre-submit hook reached.', $form->getErrors()['_form']);
    }

    public function testPostSubmitHookIsDispatchedDuringHandleRequest(): void
    {
        $hooks = (new FormHookKernel())->register(new InvalidateOnPostSubmitHook());
        $factory = new FormFactory(hookKernel: $hooks);
        $builder = $factory->createBuilder('demo');
        $builder->add('name', 'TextType');
        $form = $builder->getForm();

        $form->handleRequest(new ArrayRequest('POST', [
            'demo' => ['name' => 'Alice'],
        ]));

        self::assertTrue($form->isSubmitted());
        self::assertArrayHasKey('_form', $form->getErrors());
        self::assertContains('Post-submit hook reached.', $form->getErrors()['_form']);
    }
}
