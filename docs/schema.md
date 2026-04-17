[↑ Retour au sommaire docs](index.md)

> Breadcrumb: [Docs](index.md) / schema.md

# Schema

## Contrats introduits
- `SchemaExporterInterface`
- `FormSchemaManager`

## Runtime V4.3.4
La ligne V4.3.4 introduit un exporteur runtime exploitable :
- `Infrastructure\Schema\ArraySchemaExporter`

## Contenu du schéma exporté
- nom du formulaire
- méthode
- champs
- type
- label
- required
- choices
- contraintes simples

## Hooks avancés
- `before_schema_export`
- `after_schema_export`

[↑ Retour au sommaire docs](index.md)
