<?php
declare(strict_types=1);
namespace Iriven\PhpFormGenerator\Application\Cli;
use Iriven\PhpFormGenerator\Application\Headless\HeadlessFormState;
use Iriven\PhpFormGenerator\Application\Headless\HeadlessResponseBuilder;
/** @api */
final class DebugHeadlessContractCommand implements CliCommandInterface
{
    public function name(): string { return 'debug:headless-contract'; }
    /** @param array<int, string> $args */
    public function run(array $args = []): string
    {
        $payload = (new HeadlessResponseBuilder())->build(new HeadlessFormState(false, false, [], [], ['mode' => 'contract']));
        return json_encode($payload, JSON_PRETTY_PRINT) ?: '{}';
    }
}
