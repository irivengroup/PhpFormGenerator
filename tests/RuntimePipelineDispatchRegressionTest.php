<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use ArrayObject;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormHookKernel;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormRuntimePipeline;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class RuntimePipelineDispatchRegressionTest extends TestCase
{
    public function testBeforeAndAfterExportHooksCanBeObserved(): void
    {
        $captured = new ArrayObject();

        $hooks = new FormHookKernel();
        $hooks->register(new class($captured) implements \Iriven\Fluxon\Domain\Contract\FormHookInterface {
            /** @param ArrayObject<int, string> $captured */
            public function __construct(private ArrayObject $captured) {}
            public static function getName(): string { return 'before_export'; }
            public function __invoke(\Iriven\Fluxon\Domain\Form\Form $form, array $context = []): void { $this->captured->append('before_export'); }
        });
        $hooks->register(new class($captured) implements \Iriven\Fluxon\Domain\Contract\FormHookInterface {
            /** @param ArrayObject<int, string> $captured */
            public function __construct(private ArrayObject $captured) {}
            public static function getName(): string { return 'after_export'; }
            public function __invoke(\Iriven\Fluxon\Domain\Form\Form $form, array $context = []): void { $this->captured->append('after_export'); }
        });

        $builder = (new FormFactory())->createBuilder('contact');
        $builder->add('name', 'TextType');
        $form = $builder->getForm();

        $runtime = new FormRuntimeContext($form, 'default', 'RendererClass', ['variant' => 'compact']);
        $schema = (new FormSchemaManager(new ArraySchemaExporter(), $hooks))->export($form, $runtime);

        self::assertArrayHasKey('runtime', $schema);
        self::assertSame(['before_export', 'after_export'], $captured->getArrayCopy());
    }

    public function testRuntimePipelineDispatchCanUseStructuredContext(): void
    {
        $captured = new ArrayObject();

        $hooks = new FormHookKernel();
        $hooks->register(new class($captured) implements \Iriven\Fluxon\Domain\Contract\FormHookInterface {
            /** @param ArrayObject<int, string> $captured */
            public function __construct(private ArrayObject $captured) {}
            public static function getName(): string { return 'before_render'; }
            public function __invoke(\Iriven\Fluxon\Domain\Form\Form $form, array $context = []): void
            {
                if (($context['runtime'] ?? null) instanceof FormRuntimeContext) {
                    $this->captured->append('runtime');
                }
            }
        });

        $pipeline = new FormRuntimePipeline($hooks);
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $runtime = new FormRuntimeContext($form, 'default', 'RendererClass', ['variant' => 'compact']);

        $pipeline->dispatch('before_render', $form, ['runtime' => $runtime]);

        self::assertSame(['runtime'], $captured->getArrayCopy());
    }
}
