<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormRuntimeContext;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Application\Headless\HeadlessFormProcessor;
use Iriven\Fluxon\Application\PublicApi\FullPipelineConsistencyInspector;
use Iriven\Fluxon\Application\PublicApi\UnifiedSchemaExporter;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;
final class FullPipelineConsistencyTest extends TestCase
{
    public function testSdkAndHeadlessStayConsistent(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $runtime = new FormRuntimeContext($form, 'default', 'RendererClass', ['channel' => 'headless']);
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()));
        $inspector = new FullPipelineConsistencyInspector(new UnifiedSchemaExporter($sdk), new HeadlessFormProcessor());
        self::assertTrue($inspector->isConsistent($form, $runtime));
    }
}
