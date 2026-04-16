# Hooks

## Contrats introduits
- `HookInterface`
- `FormHookInterface`

## Runtime V4.3.2
La ligne V4.3.2 fournit :
- `InMemoryHookRegistry`
- `FormHookKernel`
- dispatch des hooks dans le cycle de vie complet du formulaire

## Hooks lifecycle disponibles
- `post_build`
- `pre_handle_request`
- `pre_submit`
- `validation_error`
- `post_submit`
- `post_handle_request`

## Exemple
```php
$hooks = (new FormHookKernel())
    ->register(new MyHook());

$factory = new FormFactory(hookKernel: $hooks);
$form = $factory->createBuilder('demo')->getForm();
```
