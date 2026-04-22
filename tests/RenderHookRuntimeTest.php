<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormHookKernel;
use Iriven\Fluxon\Application\FormRenderManager;
use Iriven\Fluxon\Application\FormThemeKernel;
use Iriven\Fluxon\Presentation\Html\HtmlRendererFactory;
use Iriven\Fluxon\Tests\Fixtures\Hook\AfterRenderHook;
use Iriven\Fluxon\Tests\Fixtures\Hook\BeforeRenderHook;
use PHPUnit\Framework\TestCase;

final class RenderHookRuntimeTest extends TestCase
{
    public function testRenderHooksAreDispatched(): void
    {
        $hooks = (new FormHookKernel())
            ->register(new BeforeRenderHook())
            ->register(new AfterRenderHook());

        $factory = new FormFactory(hookKernel: $hooks);
        $builder = $factory->createBuilder('contact');
        $builder->add('name', 'TextType', ['label' => 'Name']);
        $form = $builder->getForm();

        $manager = new FormRenderManager(new HtmlRendererFactory(new FormThemeKernel()), $hooks);
        $html = $manager->render($form, 'default');

        self::assertIsString($html);
        self::assertArrayHasKey('_form', $form->getErrors());
        self::assertContains('Before render hook reached.', $form->getErrors()['_form']);
        self::assertContains('After render hook reached.', $form->getErrors()['_form']);
    }
}
