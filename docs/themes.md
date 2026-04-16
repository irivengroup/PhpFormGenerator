[↑ Retour au sommaire docs](index.md)

> Breadcrumb: [Docs](index.md) / themes.md

# Themes

## Runtime V4.3.3
La ligne V4.3.3 confirme le branchement runtime des thèmes via :
- `FormThemeKernel`
- `HtmlRendererFactory`

## Themes enregistrés par défaut
- `default`
- `bootstrap5`
- `tailwind`

## Themes custom V4.3.3
- alias custom supportés
- fallback documenté
- registre de thèmes exploitable par extension

## Exemple
```php
$themes = new FormThemeKernel();
$renderer = (new HtmlRendererFactory($themes))->create('tailwind');
```


## Hardening V4.3.5
- documentation dédiée aux thèmes custom
- recommandations de fallback et de conventions CSS

[↑ Retour au sommaire docs](index.md)
