# Plugin Ecosystem

## Overview
Extends:
- field types
- form types
- runtime behavior

## ExtensionInterface

```php
interface ExtensionInterface
{
    public function supports(string $type): bool;
    public function apply(array $options): array;
}
```
