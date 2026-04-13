<?php
declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Form;

use Iriven\PhpFormGenerator\Domain\Contract\CsrfManagerInterface;
use Iriven\PhpFormGenerator\Domain\Contract\DataMapperInterface;
use Iriven\PhpFormGenerator\Domain\Contract\EventDispatcherInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FieldTypeInterface;
use Iriven\PhpFormGenerator\Domain\Contract\FormTypeInterface;
use Iriven\PhpFormGenerator\Infrastructure\Mapping\ObjectDataMapper;

final class FormBuilder
{
    private Form $form;

    /** @var list<Fieldset> */
    private array $fieldsetStack = [];

    public function __construct(
        string $name,
        array $options = [],
        ?CsrfManagerInterface $csrfManager = null,
        ?EventDispatcherInterface $dispatcher = null,
        ?DataMapperInterface $dataMapper = null,
    ) {
        if ($dataMapper === null && isset($options['data_class']) && is_string($options['data_class']) && $options['data_class'] !== '') {
            $dataMapper = new ObjectDataMapper($options['data_class']);
        }

        $this->form = new Form($name, $options, $csrfManager, $dispatcher, $dataMapper);
    }

    public function adoptForm(Form $form): self
    {
        $this->form = $form;
        return $this;
    }

    public function add(string $name, string $type, array $options = []): self
    {
        if (class_exists($type) && is_subclass_of($type, FormTypeInterface::class)) {
            return $this->addCompoundForm($name, $type, $options);
        }

        /** @var FieldTypeInterface $instance */
        $instance = new $type();
        $normalized = $instance->normalizeOptions($options);

        $field = new FieldDefinition(
            $name,
            $instance,
            $normalized,
            $normalized['constraints'] ?? [],
            $normalized['data'] ?? null,
        );

        if ($field->isCompound() && !$field->isCollection()) {
            $entryType = $normalized['entry_type'] ?? null;
            if (isset($normalized['form_type']) && is_string($normalized['form_type'])) {
                $this->attachCompoundChildForm($field, $normalized['form_type'], $normalized);
            } elseif ($entryType !== null) {
                // CollectionType is initialized lazily at setData/submit time.
            }
        }

        $current = $this->currentFieldset();
        if ($current !== null) {
            $current->addField($field);
        }

        $this->form->addField($field);

        return $this;
    }

    public function addFieldset(array $options = []): self
    {
        $fieldset = new Fieldset($options);
        $current = $this->currentFieldset();

        if ($current !== null) {
            $current->addChild($fieldset);
        } else {
            $this->form->addFieldset($fieldset);
        }

        $this->fieldsetStack[] = $fieldset;

        return $this;
    }

    public function endFieldset(): self
    {
        array_pop($this->fieldsetStack);

        return $this;
    }

    public function setData(mixed $data): self
    {
        $this->form->setData($data);

        return $this;
    }

    public function addFormConstraint(callable $constraint): self
    {
        $this->form->addFormConstraint($constraint);

        return $this;
    }

    public function useType(string $typeClass, array $options = []): self
    {
        /** @var FormTypeInterface $type */
        $type = new $typeClass();
        $type->buildForm($this, $options);

        return $this;
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    private function addCompoundForm(string $name, string $typeClass, array $options = []): self
    {
        $fieldType = new \Iriven\PhpFormGenerator\Domain\Field\FormType();
        $normalized = $fieldType->normalizeOptions($options + ['form_type' => $typeClass]);

        $field = new FieldDefinition(
            $name,
            $fieldType,
            $normalized,
            $normalized['constraints'] ?? [],
            null
        );

        $this->attachCompoundChildForm($field, $typeClass, $normalized);

        $current = $this->currentFieldset();
        if ($current !== null) {
            $current->addField($field);
        }

        $this->form->addField($field);

        return $this;
    }

    /**
     * @param array<string,mixed> $options
     */
    private function attachCompoundChildForm(FieldDefinition $field, string $typeClass, array $options): void
    {
        $childMapper = null;
        if (isset($options['data_mapper']) && $options['data_mapper'] instanceof DataMapperInterface) {
            $childMapper = $options['data_mapper'];
        } elseif (isset($options['data_class']) && is_string($options['data_class']) && $options['data_class'] !== '') {
            $childMapper = new ObjectDataMapper($options['data_class']);
        }

        $childBuilder = new self(
            $field->name,
            ['method' => $this->form->options()['method'] ?? 'POST'] + $options,
            $this->form->csrfManager(),
            $this->form->dispatcher(),
            $childMapper
        );

        $childForm = $childBuilder->getForm();
        $childForm->setParent($this->form, $field->name, $field);

        /** @var FormTypeInterface $type */
        $type = new $typeClass();
        $type->buildForm($childBuilder, $options);

        $field->setCompoundForm($childBuilder->getForm());
    }

    private function currentFieldset(): ?Fieldset
    {
        if ($this->fieldsetStack === []) {
            return null;
        }

        return $this->fieldsetStack[array_key_last($this->fieldsetStack)];
    }
}
