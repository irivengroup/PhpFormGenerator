<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormHookKernel;
use Iriven\Fluxon\Application\FormRenderManager;
use Iriven\Fluxon\Application\FormThemeKernel;
use Iriven\Fluxon\Presentation\Html\HtmlRendererFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class AdvancedRenderingRegressionTest extends TestCase
{
    public function testRenderManagerCanRenderWithoutHookKernel(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType', ['label' => 'Name']);
        $html = (new FormRenderManager(new HtmlRendererFactory(new FormThemeKernel())))->render($builder->getForm(), 'default');

        self::assertIsString($html);
        self::assertStringContainsString('<form', $html);
    }

    public function testUnknownThemeFallsBackToRenderableOutput(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');
        $html = (new FormRenderManager(new HtmlRendererFactory(new FormThemeKernel())))->render($builder->getForm(), 'unknown-theme');

        self::assertIsString($html);
        self::assertStringContainsString('<form', $html);
    }

    public function testRenderHookFailureCanBubble(): void
    {
        $hooks = (new FormHookKernel())->register(new class implements \Iriven\Fluxon\Domain\Contract\FormHookInterface {
            public static function getName(): string { return 'before_render'; }
            public function __invoke(\Iriven\Fluxon\Domain\Form\Form $form, array $context = []): void
            {
                throw new RuntimeException('render hook failure');
            }
        });

        $builder = (new FormFactory(hookKernel: $hooks))->createBuilder('contact');
        $builder->add('name', 'TextType');
        $manager = new FormRenderManager(new HtmlRendererFactory(new FormThemeKernel()), $hooks);

        $this->expectException(RuntimeException::class);
        $manager->render($builder->getForm(), 'default');
    }

    public function testRenderWithExistingGlobalErrorsStillProducesHtml(): void
    {
        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');
        $form = $builder->getForm();
        $form->appendError('_form', 'Global error');

        $html = (new FormRenderManager(new HtmlRendererFactory(new FormThemeKernel())))->render($form, 'default');

        self::assertStringContainsString('Global error', $html);
    }
}
