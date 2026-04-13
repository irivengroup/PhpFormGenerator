# PhpFormGenerator V3.2 Production Hardening

Cette version pousse le starter V3.1 vers une base plus industrialisée.

## Ajouts V3.2

- hardening outillage qualité
- configuration Scrutinizer
- GitHub Actions CI
- PHPStan niveau 8
- Rector
- Infection
- support fieldsets conservé
- renderer HTML échappé
- request handling, validation et CSRF de base
- point d'extension pour debug metadata

## Exemple rapide

```php
use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Domain\Field\EmailType;
use Iriven\PhpFormGenerator\Domain\Field\TextType;

$factory = new FormFactory();
$builder = $factory->createBuilder('contact');

$form = $builder
    ->addFieldset([
        'legend' => 'Contact',
        'description' => 'Informations principales',
    ])
    ->add('name', TextType::class, ['label' => 'Nom'])
    ->add('email', EmailType::class, ['label' => 'Email'])
    ->endFieldset()
    ->getForm();
```

## Scripts

- `composer qa`
- `composer analyse`
- `composer test`
- `composer refactor`
- `composer mutate`
