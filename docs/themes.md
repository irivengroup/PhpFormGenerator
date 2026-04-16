# Themes

## Registry de thèmes
La ligne V4.3.0 introduit :
- `ThemeRegistryInterface`
- `InMemoryThemeRegistry`
- `FormThemeKernel`

## Thèmes enregistrés par défaut
- `default`
- `bootstrap5`
- `tailwind`

## Exemple
```php
$themes = new FormThemeKernel();
$theme = $themes->themes()->resolve('tailwind');
```
