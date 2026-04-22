<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\FormSchemaManager;
use Iriven\Fluxon\Application\Frontend\FrontendSdk;
use Iriven\Fluxon\Application\PublicApi\PublicApiStabilityChecker;
use Iriven\Fluxon\Application\PublicApi\UnifiedSchemaExporter;
use Iriven\Fluxon\Infrastructure\Schema\ArraySchemaExporter;
use PHPUnit\Framework\TestCase;
final class PublicApiStabilityTest extends TestCase
{
    public function testUnifiedSchemaContainsAllStableKeys(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $sdk = new FrontendSdk(new FormSchemaManager(new ArraySchemaExporter()));
        $schema = (new UnifiedSchemaExporter($sdk))->export($form);
        self::assertTrue((new PublicApiStabilityChecker())->isStable($schema));
    }
}
