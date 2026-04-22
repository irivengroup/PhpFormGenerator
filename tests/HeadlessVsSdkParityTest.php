<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Application\Headless\HeadlessFormProcessor;
use Iriven\Fluxon\Application\PublicApi\UnifiedSchemaExporter;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;
final class HeadlessVsSdkParityTest extends TestCase
{
    public function testBothPipelinesExposeSchemaVersion(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()));
        $unified = (new UnifiedSchemaExporter($sdk))->export($form);
        $headless = (new HeadlessFormProcessor())->schema($form);
        self::assertArrayHasKey('version', $unified['schema']);
        self::assertArrayHasKey('version', $headless['schema']);
    }
}
