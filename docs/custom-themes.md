[↑ Retour au sommaire docs](index.md)

> Breadcrumb: [Docs](index.md) / Custom Themes

# Custom Themes

## Objectif
Expliquer comment enregistrer et utiliser un thème HTML custom.

## Exemple minimal
```php
$themes = (new FormThemeKernel())
    ->register('minimal', new MinimalTheme());

$renderer = (new HtmlRendererFactory($themes))->create('minimal');
```

## Recommandations
- choisir un alias explicite
- prévoir un fallback côté appelant
- garder une convention cohérente sur les classes CSS

[↑ Retour au sommaire docs](index.md)
