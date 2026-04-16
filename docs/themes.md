# Themes

## Runtime V4.3.2
La ligne V4.3.2 confirme le branchement runtime des thèmes via :
- `FormThemeKernel`
- `HtmlRendererFactory`

## Exemple
```php
$themes = new FormThemeKernel();
$renderer = (new HtmlRendererFactory($themes))->create('tailwind');
```
