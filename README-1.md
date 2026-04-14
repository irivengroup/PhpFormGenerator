# PhpFormGenerator

## Included application form types

The project now ships with reusable application-level form types:

- `InvoiceType`
- `RegistrationType`
- `ContactType`
- supporting types such as `CustomerType` and `InvoiceLineType`

### `ContactType`
`ContactType` is intended for website contact pages, support forms, lead generation, and customer communication flows.

Included fields:
- `name`
- `email`
- `phone`
- `country`
- `subject`
- `message`
- `captcha`
- submit button

Highlights:
- fieldsets for clearer rendering
- required constraints on key fields
- email validation
- textarea with minimum content length
- integrated case-sensitive alphanumeric captcha
- suitable for HTML rendering or server-side processing examples

## CSRF protection defaults

CSRF protection is enabled by default across the framework.

You do not need to declare it explicitly for standard usage.

Default behavior:
- `csrf_protection` is automatically set to `true`
- users only need to pass `csrf_protection => false` when they intentionally want to disable it

Examples:

```php
$form = $factory->create(ContactType::class);
```

This enables CSRF protection automatically.

To disable it explicitly:

```php
$form = $factory->create(ContactType::class, null, [
    'csrf_protection' => false,
]);
```

The same rule applies to the fluent `FormGenerator` API:
- `open()` enables CSRF protection by default
- passing `['csrf_protection' => false]` disables it intentionally
