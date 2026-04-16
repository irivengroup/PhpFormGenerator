# V4.1.3 RC1 – Consolidation plugins, non-régression et release candidate

## Objectif
Stabiliser définitivement la couche plugins avant une release V4 stable plus large.

## Inclus
- tests d’intégration plugins
- tests de non-régression plugins
- hardening registries
- documentation wiki renforcée
- préparation release candidate

## Vérifications recommandées
```bash
composer dump-autoload -o
vendor/bin/phpstan analyse src tests
vendor/bin/phpunit --colors=never
composer test:coverage
composer release:check
```
