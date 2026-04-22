<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\Headless\HeadlessFormProcessor;
use Iriven\Fluxon\Application\Mapping\FormExtractionManager;
use PHPUnit\Framework\TestCase;
final class MappingHeadlessIntegrationTest extends TestCase
{
    public function testHeadlessSchemaAndMappingCanCoexist(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new HeadlessFormProcessor())->schema($form);
        $data = (new FormExtractionManager())->extract(['email' => 'john@example.com']);
        self::assertArrayHasKey('schema', $schema);
        self::assertSame('john@example.com', $data['email']);
    }
}
