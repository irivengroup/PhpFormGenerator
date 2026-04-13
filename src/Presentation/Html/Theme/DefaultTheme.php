<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Presentation\Html\Theme;

final class DefaultTheme implements ThemeInterface
{
    public function formClass(): string { return 'pfg-form'; }
    public function rowClass(): string { return 'pfg-row'; }
    public function labelClass(): string { return 'pfg-label'; }
    public function inputClass(string $type): string { return 'pfg-input pfg-input-' . $type; }
    public function errorClass(): string { return 'pfg-error'; }
}
