<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Field\CheckboxType;
use Iriven\PhpFormGenerator\Domain\Field\CollectionType;
use Iriven\PhpFormGenerator\Domain\Field\CountryType;
use Iriven\PhpFormGenerator\Domain\Field\DateTimeType;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\FileType;
use Iriven\PhpFormGenerator\Domain\Field\FloatType;
use Iriven\PhpFormGenerator\Domain\Field\HiddenType;
use Iriven\PhpFormGenerator\Domain\Field\IntegerType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Field\TextareaType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Domain\Form\Form;
use Iriven\PhpFormGenerator\Domain\Form\FormBuilder;

final class FormGenerator
{
    private FormBuilder $builder;

    public function __construct(string $name = 'form')
    {
        $this->builder = new FormBuilder($name);
    }

    public function open(array $options = []): self { return $this; }
    public function addText(string $name, array $options = []): self { $this->builder->add($name, TextType::class, $options); return $this; }
    public function addEmail(string $name, array $options = []): self { $this->builder->add($name, EmailType::class, $options); return $this; }
    public function addTextarea(string $name, array $options = []): self { $this->builder->add($name, TextareaType::class, $options); return $this; }
    public function addCheckbox(string $name, array $options = []): self { $this->builder->add($name, CheckboxType::class, $options); return $this; }
    public function addHidden(string $name, array $options = []): self { $this->builder->add($name, HiddenType::class, $options); return $this; }
    public function addSubmit(string $name = 'submit', array $options = []): self { $this->builder->add($name, SubmitType::class, $options); return $this; }
    public function addFile(string $name, array $options = []): self { $this->builder->add($name, FileType::class, $options); return $this; }
    public function addCountries(string $name, array $options = []): self { $this->builder->add($name, CountryType::class, $options); return $this; }
    public function addDateTime(string $name, array $options = []): self { $this->builder->add($name, DateTimeType::class, $options); return $this; }
    public function addDatetimeLocal(string $name, array $options = []): self { return $this->addDateTime($name, $options); }
    public function addInteger(string $name, array $options = []): self { $this->builder->add($name, IntegerType::class, $options); return $this; }
    public function addFloat(string $name, array $options = []): self { $this->builder->add($name, FloatType::class, $options); return $this; }
    public function addCollection(string $name, array $options = []): self { $this->builder->add($name, CollectionType::class, $options); return $this; }
    public function addFieldset(array $options = []): self { $this->builder->addFieldset($options); return $this; }
    public function endFieldset(): self { $this->builder->endFieldset(); return $this; }
    public function getForm(): Form { return $this->builder->getForm(); }
}
