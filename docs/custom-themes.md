[↑ Retour au sommaire docs](index.md)

> Breadcrumb: [Docs](index.md) / custom-themes.md

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


## Stabilisation V4.4.0
Les thèmes custom et leur résolution runtime sont promus comme capacités avancées stables.


## Orientation V4.5.0
Les thèmes custom sont conçus pour être combinés avec les hooks de rendu.

[↑ Retour au sommaire docs](index.md)
