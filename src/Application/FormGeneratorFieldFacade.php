<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Application;

use Iriven\Fluxon\Application\FormGenerator\AttributeNormalizer;
use Iriven\Fluxon\Application\FormGenerator\BasicFieldFacade;
use Iriven\Fluxon\Application\FormGenerator\ChoiceFieldFacade;
use Iriven\Fluxon\Domain\Form\FormBuilder;

final class FormGeneratorFieldFacade
{
    private BasicFieldFacade $basicFields;
    private ChoiceFieldFacade $choiceFields;

    public function __construct(FormBuilder $builder)
    {
        $normalizer = new AttributeNormalizer();
        $this->basicFields = new BasicFieldFacade($builder, $normalizer);
        $this->choiceFields = new ChoiceFieldFacade($this->basicFields);
    }

    /** @param array<string, mixed> $attributes */
    public function add(string $name, string $typeClass, array $attributes = []): void { $this->basicFields->add($name, $typeClass, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addText(string $name, array $attributes = []): void { $this->basicFields->addText($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addEmail(string $name, array $attributes = []): void { $this->basicFields->addEmail($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addTextarea(string $name, array $attributes = []): void { $this->basicFields->addTextarea($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addEditor(string $name, array $attributes = []): void { $this->basicFields->addEditor($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addHidden(string $name, array $attributes = []): void { $this->basicFields->addHidden($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addSubmit(string $name, array $attributes = []): void { $this->basicFields->addSubmit($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addButton(string $name, array $attributes = []): void { $this->basicFields->addButton($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addReset(string $name, array $attributes = []): void { $this->basicFields->addReset($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addFile(string $name, array $attributes = []): void { $this->basicFields->addFile($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addAudio(string $name, array $attributes = []): void { $this->basicFields->addAudio($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addImage(string $name, array $attributes = []): void { $this->basicFields->addImage($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addVideo(string $name, array $attributes = []): void { $this->basicFields->addVideo($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addCountries(string $name, array $attributes = []): void { $this->basicFields->addCountries($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addCountry(string $name, array $attributes = []): void { $this->basicFields->addCountry($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addDatetime(string $name, array $attributes = []): void { $this->basicFields->addDatetime($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addDatetimeLocal(string $name, array $attributes = []): void { $this->basicFields->addDatetimeLocal($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addDate(string $name, array $attributes = []): void { $this->basicFields->addDate($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addTime(string $name, array $attributes = []): void { $this->basicFields->addTime($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addMonth(string $name, array $attributes = []): void { $this->basicFields->addMonth($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addWeek(string $name, array $attributes = []): void { $this->basicFields->addWeek($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addInteger(string $name, array $attributes = []): void { $this->basicFields->addInteger($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addFloat(string $name, array $attributes = []): void { $this->basicFields->addFloat($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addNumber(string $name, array $attributes = []): void { $this->basicFields->addNumber($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addRange(string $name, array $attributes = []): void { $this->basicFields->addRange($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addColor(string $name, array $attributes = []): void { $this->basicFields->addColor($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addPassword(string $name, array $attributes = []): void { $this->basicFields->addPassword($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addPhone(string $name, array $attributes = []): void { $this->basicFields->addPhone($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addSearch(string $name, array $attributes = []): void { $this->basicFields->addSearch($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addUrl(string $name, array $attributes = []): void { $this->basicFields->addUrl($name, $attributes); }
    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addRadio(string $name, array $choices = [], array $attributes = []): void { $this->choiceFields->addRadio($name, $choices, $attributes); }
    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addCheckbox(string $name, array $choices = [], array $attributes = []): void { $this->choiceFields->addCheckbox($name, $choices, $attributes); }
    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addSelect(string $name, array $choices = [], array $attributes = []): void { $this->choiceFields->addSelect($name, $choices, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addYesNo(string $name, array $attributes = []): void { $this->choiceFields->addYesNo($name, $attributes); }
    /**
     * @param array<string, mixed> $choices
     * @param array<string, mixed> $attributes
     */
    public function addDatalist(string $name, array $choices = [], array $attributes = []): void { $this->choiceFields->addDatalist($name, $choices, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addCaptcha(string $name, array $attributes = []): void { $this->basicFields->addCaptcha($name, $attributes); }
    /** @param array<string, mixed> $attributes */
    public function addCollection(string $name, array $attributes = []): void { $this->basicFields->addCollection($name, $attributes); }
}
