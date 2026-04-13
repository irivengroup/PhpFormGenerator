# Architecture V3.3

## Couches

- `Application` : `FormFactory`, façade `FormGenerator`
- `Domain` : `Form`, `FormBuilder`, `FieldDefinition`, contraintes, field types
- `Infrastructure` : request, CSRF, mapping
- `Presentation` : rendu HTML et thèmes

## Nouveautés V3.3

### Form tree
Un formulaire peut contenir :
- des champs simples
- un sous-formulaire compound
- une collection d'entrées

### Nested forms
`FormBuilder::add('customer', CustomerType::class)` construit un enfant récursif.

### Collections
`CollectionType` construit une liste d'entrées :
- entrée scalaire
- ou sous-formulaire si `entry_type` implémente `FormTypeInterface`

### Renderer
Le renderer calcule les noms HTML de manière récursive :
- `invoice[customer][name]`
- `invoice[items][0][label]`

### Mapping
Le mapping final retourne :
- tableau associatif par défaut
- ou objet si `data_class` est fourni
