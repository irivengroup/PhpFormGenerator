# V4.3.0 – Nouvelles capacités (feature line)

## Objectif
Ouvrir une nouvelle ligne d’évolution orientée capacités supplémentaires sans remettre en cause la stabilité de V4.2.x.

## Inclus
- hooks officiels (contrats)
- registry de thèmes
- gestionnaire de schéma
- documentation feature line

## Validation recommandée
```bash
composer dump-autoload -o
composer validate:full
vendor/bin/phpunit --colors=never
vendor/bin/phpstan analyse src tests
```
