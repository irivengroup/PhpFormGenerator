# V4.3.2 – Intégration hooks dans lifecycle complet

## Objectif
Poursuivre la feature line V4.3.x avec une intégration plus profonde des hooks dans le cycle de vie complet du formulaire.

## Inclus
- runtime thèmes branché via `HtmlRendererFactory`
- runtime hooks exécutable via `FormHookKernel`
- hooks lifecycle :
  - `post_build`
  - `pre_handle_request`
  - `pre_submit`
  - `validation_error`
  - `post_submit`
  - `post_handle_request`

## Validation recommandée
```bash
composer dump-autoload -o
composer validate:full
vendor/bin/phpunit --colors=never
vendor/bin/phpstan analyse src tests
```
