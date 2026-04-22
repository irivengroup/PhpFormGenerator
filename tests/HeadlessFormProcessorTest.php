<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Tests;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\Headless\HeadlessFormProcessor;
use PHPUnit\Framework\TestCase;
final class HeadlessFormProcessorTest extends TestCase
{
    public function testHeadlessSchemaCanBeExported(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $schema = (new HeadlessFormProcessor())->schema($form);
        self::assertArrayHasKey('schema', $schema);
        self::assertArrayHasKey('fields', $schema);
    }
    public function testHeadlessValidationReturnsStructuredState(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $result = (new HeadlessFormProcessor())->validate($form, ['email' => 'john@example.com']);
        self::assertTrue($result['state']['submitted']);
        self::assertTrue($result['state']['valid']);
        self::assertSame('validate', $result['metadata']['mode']);
    }
    public function testHeadlessInvalidSubmissionReturnsErrors(): void
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $result = (new HeadlessFormProcessor())->invalid($form, ['email' => 'bad'], ['email' => ['Invalid email']]);
        self::assertFalse($result['state']['valid']);
        self::assertSame(['Invalid email'], $result['errors']['email']);
    }
}
