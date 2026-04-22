<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Form;

use Iriven\Fluxon\Domain\Contract\ConstraintInterface;
use Iriven\Fluxon\Domain\Contract\EventDispatcherInterface;
use Iriven\Fluxon\Domain\Contract\EventSubscriberInterface;
use Iriven\Fluxon\Infrastructure\Event\EventDispatcher;
use Iriven\Fluxon\Infrastructure\Extension\ExtensionRegistry;
use Iriven\Fluxon\Infrastructure\Security\SessionCaptchaManager;

final class FormBuilder
{
    /** @var array<string, FieldConfig> */
    private array $fields = [];

    /** @var array<int, Fieldset> */
    private array $fieldsets = [];

    /** @var array<int, string> */
    private array $fieldsetStack = [];

    /** @var array<int, ConstraintInterface> */
    private array $formConstraints = [];

    private EventDispatcherInterface $eventDispatcher;
    private ExtensionRegistry $extensionRegistry;
    private FormBuilderFieldDefinitionFactory $fieldDefinitionFactory;
    private FormBuilderFieldsetManager $fieldsetManager;
    private FormBuilderFormFactory $formFactory;

    /** @param array<string, mixed> $options */
    public function __construct(
        private readonly string $name = 'form',
        private mixed $data = null,
        private array $options = [],
    ) {
        $dispatcher = $this->options['event_dispatcher'] ?? null;
        $this->eventDispatcher = $dispatcher instanceof EventDispatcherInterface ? $dispatcher : new EventDispatcher();

        if (!isset($this->options['captcha_manager'])) {
            $this->options['captcha_manager'] = new SessionCaptchaManager();
        }

        $registry = $this->options['extension_registry'] ?? null;
        $this->extensionRegistry = $registry instanceof ExtensionRegistry ? $registry : new ExtensionRegistry();

        $this->fieldDefinitionFactory = new FormBuilderFieldDefinitionFactory($this->eventDispatcher, $this->extensionRegistry);
        $this->fieldsetManager = new FormBuilderFieldsetManager();
        $this->formFactory = new FormBuilderFormFactory($this->extensionRegistry);
    }

    /** @param array<string, mixed> $options */
    public function mergeOptions(array $options): self
    {
        if (!array_key_exists('csrf_protection', $options) && !array_key_exists('csrf_protection', $this->options)) {
            $options['csrf_protection'] = true;
        }

        $this->options = array_replace_recursive($this->options, $options);

        return $this;
    }

    /**
     * @param string $typeClass
     * @param array<string, mixed> $options
     */
    public function add(string $name, string $typeClass, array $options = []): self
    {
        [$fieldConfig, $this->options] = $this->fieldDefinitionFactory->create($name, $typeClass, $options, $this->options);
        $this->fields[$name] = $fieldConfig;
        $this->fieldsets = $this->fieldsetManager->attachField($this->fieldsets, $this->fieldsetStack, $name);

        return $this;
    }

    /** @param array<string, mixed> $options */
    public function addFieldset(array $options = []): self
    {
        [$this->fieldsets, $this->fieldsetStack] = $this->fieldsetManager->addFieldset($this->fieldsets, $this->fieldsetStack, $options);

        return $this;
    }

    public function endFieldset(): self
    {
        $this->fieldsetStack = $this->fieldsetManager->endFieldset($this->fieldsetStack);

        return $this;
    }

    public function addFormConstraint(ConstraintInterface $constraint): self
    {
        $this->formConstraints[] = $constraint;

        return $this;
    }

    public function addEventListener(string $eventName, callable $listener): self
    {
        $this->eventDispatcher->addListener($eventName, $listener);

        return $this;
    }

    public function addEventSubscriber(EventSubscriberInterface $subscriber): self
    {
        $this->eventDispatcher->addSubscriber($subscriber);

        return $this;
    }

    /** @return array<string, FieldConfig> */
    public function all(): array
    {
        return $this->fields;
    }

    public function getForm(): Form
    {
        return $this->formFactory->create(
            $this->name,
            $this->options,
            $this->fields,
            $this->eventDispatcher,
            $this->data,
            $this->fieldsets,
            $this->formConstraints,
        );
    }
}
