<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Presentation\Html\Theme;

interface ThemeInterface
{
    public function formClass(): string;

    public function rowClass(): string;

    public function labelClass(): string;

    public function inputClass(string $type): string;

    public function errorClass(): string;
}
