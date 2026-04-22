<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Presentation\Html\Theme;

class DefaultTheme implements ThemeInterface
{
    public function formClass(): string { return 'pfg-form'; }
    public function rowClass(): string { return 'pfg-row'; }
    public function labelClass(): string { return 'pfg-label'; }
    public function inputClass(): string { return 'pfg-input'; }
    public function errorClass(): string { return 'pfg-error'; }
    public function fieldsetClass(): string { return 'pfg-fieldset'; }
}
