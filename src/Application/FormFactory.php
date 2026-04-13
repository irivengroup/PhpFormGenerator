<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Application;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\DataMapperInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Domain\Validation\ValidatorEngine;

final class FormFactory
{
    public function __construct(
        private readonly DataMapperInterface $dataMapper,
        private readonly CsrfManagerInterface $csrfManager,
        private readonly ValidatorEngine $validator = new ValidatorEngine()
    ) {
    }

    public function createBuilder(): FormBuilder
    {
        return new FormBuilder($this->dataMapper, $this->csrfManager, $this->validator);
    }

    public function create(string $formTypeClass, mixed $data = null, array $options = [])
    {
        /** @var FormTypeInterface $type */
        $type = new $formTypeClass();
        $builder = $this->createBuilder()->setData($data);

        foreach (array_merge($type->configureOptions($options), $options) as $name => $value) {
            $builder->setOption($name, $value);
        }

        $type->buildForm($builder, $options);

        return $builder->getForm();
    }
}
