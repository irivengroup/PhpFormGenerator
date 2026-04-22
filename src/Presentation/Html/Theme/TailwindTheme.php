<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Presentation\Html\Theme;

final class TailwindTheme extends DefaultTheme
{
    public function formClass(): string { return 'space-y-4'; }
    public function rowClass(): string { return 'space-y-1'; }
    public function labelClass(): string { return 'block text-sm font-medium'; }
    public function inputClass(): string { return 'block w-full rounded border px-3 py-2'; }
    public function errorClass(): string { return 'text-sm text-red-600'; }
    public function fieldsetClass(): string { return 'rounded border p-4 space-y-4'; }
}
