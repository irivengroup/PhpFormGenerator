<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Field\CheckboxType;
use Iriven\PhpFormGenerator\Domain\Field\DatalistType;
use Iriven\PhpFormGenerator\Domain\Field\RadioType;
use Iriven\PhpFormGenerator\Domain\Field\SelectType;
use Iriven\PhpFormGenerator\Domain\Field\YesNoType;

final class FormGeneratorChoiceFieldFacade
{
    public function __construct(private readonly FormGeneratorBasicFieldFacade $basicFields)
    {
    }

    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addRadio(string $name, array $choices = [], array $attributes = []): void
    {
        $this->addChoiceField($name, RadioType::class, $choices, $attributes);
    }

    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addCheckbox(string $name, array $choices = [], array $attributes = []): void
    {
        $this->addChoiceField($name, CheckboxType::class, $choices, $attributes);
    }

    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addSelect(string $name, array $choices = [], array $attributes = []): void
    {
        $this->addChoiceField($name, SelectType::class, $choices, $attributes);
    }

    /** @param array<string, mixed> $attributes */
    public function addYesNo(string $name, array $attributes = []): void
    {
        $this->basicFields->add($name, YesNoType::class, $attributes);
    }

    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addDatalist(string $name, array $choices = [], array $attributes = []): void
    {
        $this->addChoiceField($name, DatalistType::class, $choices, $attributes);
    }

    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    private function addChoiceField(string $name, string $typeClass, array $choices, array $attributes): void
    {
        if ($choices !== []) {
            $attributes['choices'] = $choices;
        }

        $this->basicFields->add($name, $typeClass, $attributes);
    }
}
