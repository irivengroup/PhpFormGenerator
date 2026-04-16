[↑ Retour au sommaire docs](index.md)

> Breadcrumb: [Docs](index.md) / Lifecycle Hooks

# Lifecycle Hooks

## Objectif
Documenter les hooks utilisables le long du cycle de vie du formulaire.

## Hooks lifecycle disponibles
- `post_build`
- `pre_handle_request`
- `pre_submit`
- `validation_error`
- `post_submit`
- `post_handle_request`

## Hooks avancés
- `before_schema_export`
- `after_schema_export`

## Recommandations
- garder les hooks idempotents quand possible
- éviter les effets de bord externes dans les hooks de validation
- documenter clairement les préconditions de chaque hook


## Stabilisation V4.4.0
Les hooks lifecycle documentés ici sont promus comme capacités avancées stables.

[↑ Retour au sommaire docs](index.md)
