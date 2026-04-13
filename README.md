# PhpFormGenerator V3 Enterprise Starter Kit

Starter kit d'un framework de formulaires orienté entreprise pour PHP 8.2+.

## Ce que contient ce starter

- `FormFactory` et `FormBuilder`
- `FormTypeInterface` et `FieldTypeInterface`
- cycle minimal `handleRequest()` / `isSubmitted()` / `isValid()`
- validation de base par contraintes
- `ArrayDataMapper` et `ObjectDataMapper`
- support CSRF via `SessionCsrfManager`
- `HtmlRenderer` avec échappement systématique
- thèmes HTML simples
- champs de base : texte, email, textarea, country, checkbox, file, submit, hidden

## Exemple rapide

```php
use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Domain\Constraint\Email;
use Iriven\PhpFormGenerator\Domain\Constraint\Required;
use Iriven\PhpFormGenerator\Domain\Field\CountryType;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\SubmitType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;
use Iriven\PhpFormGenerator\Infrastructure\Mapping\ArrayDataMapper;
use Iriven\PhpFormGenerator\Infrastructure\Security\NullCsrfManager;
use Iriven\PhpFormGenerator\Presentation\Html\HtmlRenderer;
use Iriven\PhpFormGenerator\Presentation\Html\Theme\DefaultTheme;

$factory = new FormFactory(new ArrayDataMapper(), new NullCsrfManager());
$form = $factory->createBuilder()
    ->add('name', TextType::class, [
        'label' => 'Nom',
        'constraints' => [new Required()],
    ])
    ->add('email', EmailType::class, [
        'label' => 'Email',
        'constraints' => [new Required(), new Email()],
    ])
    ->add('country', CountryType::class, [
        'label' => 'Pays',
    ])
    ->add('submit', SubmitType::class, [
        'label' => 'Créer',
    ])
    ->getForm();

$form->handleRequest(new ArrayRequest([
    '_method' => 'POST',
    'name' => 'Alice',
    'email' => 'alice@example.test',
    'country' => 'FR',
]));

$renderer = new HtmlRenderer(new DefaultTheme());
echo $renderer->renderForm($form->createView());
```

## Prochaine étape

Cette base est volontairement sobre. Elle est prête à recevoir :

- collections et sous-formulaires
- events `PRE_SUBMIT` / `POST_SUBMIT`
- theming Bootstrap/Tailwind avancé
- transformers riches
- traduction et accessibilité renforcées
- contraintes métier custom
