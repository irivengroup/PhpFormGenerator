<?php
declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class FormType
{
    public function __construct(public string $name = '')
    {
    }
}
