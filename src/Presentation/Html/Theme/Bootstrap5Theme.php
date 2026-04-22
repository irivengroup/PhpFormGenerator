<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Presentation\Html\Theme;

final class Bootstrap5Theme extends DefaultTheme
{
    public function formClass(): string { return 'needs-validation'; }
    public function rowClass(): string { return 'mb-3'; }
    public function labelClass(): string { return 'form-label'; }
    public function inputClass(): string { return 'form-control'; }
    public function errorClass(): string { return 'invalid-feedback d-block'; }
    public function fieldsetClass(): string { return 'border rounded p-3 mb-3'; }
}
