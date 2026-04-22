<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Tests;

use Iriven\Fluxon\Application\Dx\CachedUnifiedSchemaExporter;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Application\PublicApi\UnifiedSchemaExporter;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;

final class CachedUnifiedSchemaExporterTest extends TestCase
{
    public function testCachedExporterReturnsStableSchema(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()));
        $exporter = new CachedUnifiedSchemaExporter(new UnifiedSchemaExporter($sdk));

        self::assertSame($exporter->export($form), $exporter->export($form));
    }
}
