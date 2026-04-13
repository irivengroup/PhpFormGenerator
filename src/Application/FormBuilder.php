<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\DataMapperInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FormBuilderInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FormInterface;
use Iriven\PhpFormGenerator\Domain\Form\Form;
use Iriven\PhpFormGenerator\Domain\Validation\ValidatorEngine;

final class FormBuilder implements FormBuilderInterface
{
    /** @var array<string, \Iriven\PhpFormGenerator\Domain\Form\Field> */
    private array $fields = [];
    private mixed $data = null;
    private array $options = [
        'method' => 'POST',
        'action' => '',
        'name' => 'form',
        'attr' => [],
        'csrf_protection' => true,
        'csrf_field_name' => '_token',
        'csrf_token_id' => 'form',
    ];

    public function __construct(
        private readonly DataMapperInterface $dataMapper,
        private readonly CsrfManagerInterface $csrfManager,
        private readonly ValidatorEngine $validator = new ValidatorEngine()
    ) {
    }

    public function add(string $name, string $typeClass, array $options = []): self
    {
        $type = new $typeClass();
        $this->fields[$name] = $type->buildField($name, $options);

        return $this;
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setOption(string $name, mixed $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function getForm(): FormInterface
    {
        return new Form($this->fields, $this->options, $this->dataMapper, $this->csrfManager, $this->validator, $this->data);
    }
}
