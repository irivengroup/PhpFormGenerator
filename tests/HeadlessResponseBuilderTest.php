<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Tests;
use Iriven\PhpFormGenerator\Application\Headless\HeadlessFormState;
use Iriven\PhpFormGenerator\Application\Headless\HeadlessResponseBuilder;
use PHPUnit\Framework\TestCase;
final class HeadlessResponseBuilderTest extends TestCase
{
    public function testHeadlessResponseContainsExpectedKeys(): void
    {
        $payload = (new HeadlessResponseBuilder())->build(new HeadlessFormState(true, true, ['name' => 'John'], [], ['mode' => 'submit']));
        self::assertSame(['state', 'payload', 'errors', 'metadata'], array_keys($payload));
    }
}
