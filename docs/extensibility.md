# Extensibilité

## Objectif

PhpFormGenerator V4.1.0 expose une base officielle orientée extension et plugins.
À partir de V4.1.1, cette base est réellement branchée dans le runtime.
À partir de V4.1.2, elle est durcie et couverte par des tests d’intégration.

## Points d’extension

- `FieldTypeRegistryInterface`
- `FormTypeRegistryInterface`
- `PluginInterface`
- `ExtensionRegistry`
- `FormPluginKernel`

## Runtime et tests

Le runtime supporte maintenant :
- alias de field types plugin
- alias de form types plugin
- extensions plugin réelles

Le projet contient désormais des tests d’intégration plugin dans `tests/`.
