<?php
declare(strict_types=1);
namespace Iriven\Fluxon\Application\Cli;
use Iriven\Fluxon\Application\FormFactory;
use Iriven\Fluxon\Application\Headless\HeadlessFormProcessor;
/** @api */
final class DebugHeadlessSubmissionCommand implements CliCommandInterface
{
    public function name(): string { return 'debug:headless-submission'; }
    /** @param array<int, string> $args */
    public function run(array $args = []): string
    {
        $form = (new FormFactory())->createBuilder('contact')->getForm();
        $payload = (new HeadlessFormProcessor())->submit($form, ['email' => 'john@example.com']);
        return json_encode($payload, JSON_PRETTY_PRINT) ?: '{}';
    }
}
