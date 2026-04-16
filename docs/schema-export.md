[↑ Retour au sommaire docs](index.md)

> Breadcrumb: [Docs](index.md) / Schema Export

# Schema Export

## Objectif
Fournir un export de schéma simple et directement exploitable.

## Exemple
```php
$factory = new FormFactory();
$builder = $factory->createBuilder('contact');
$builder->add('name', 'TextType', ['required' => true]);

$form = $builder->getForm();
$schema = (new FormSchemaManager(new ArraySchemaExporter()))->export($form);
```

## Hooks supportés
- `before_schema_export`
- `after_schema_export`


## Durcissement V4.3.5
Le schéma exporté couvre désormais aussi :
- `action`
- `help`
- `placeholder`
- `default`
- `entry_type`
- `entry_options`
- `children` pour les champs composés


## Stabilisation V4.4.0
Le format d’export de schéma est désormais traité comme stable dans la ligne avancée.

[↑ Retour au sommaire docs](index.md)
