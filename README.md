# PhpFormGenerator V3.3

Refonte enterprise du générateur historique avec :

- architecture `Application / Domain / Infrastructure / Presentation`
- field types legacy conservés
- `fieldset` natifs
- formulaires imbriqués (`FormType`)
- collections récursives (`CollectionType`)
- mapping array et objet
- validation et gestion d'erreurs profondes
- renderer HTML sécurisé

## Exemple rapide

```php
use Iriven\PhpFormGenerator\Application\FormFactory;
use Iriven\PhpFormGenerator\Infrastructure\Http\ArrayRequest;

$factory = new FormFactory();
$form = $factory->create(App\Form\InvoiceType::class, null, 'invoice', [
    'method' => 'POST',
]);

$form->handleRequest(new ArrayRequest('POST', [
    'invoice' => [
        'customer' => ['name' => 'ACME'],
        'items' => [
            ['label' => 'Audit', 'quantity' => '2'],
        ],
    ],
]));

if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->data();
}
```

## Builder simple

```php
use Iriven\PhpFormGenerator\Application\FormGenerator;

$html = (new FormGenerator())
    ->open('profile')
    ->addFieldset(['legend' => 'Identity'])
    ->addText('name')
    ->addEmail('email')
    ->endFieldset()
    ->addSubmit('save', ['label' => 'Save'])
    ->render();
```

## Fonctionnalités V3.3

- sous-formulaires via `FormTypeInterface`
- `CollectionType` avec `entry_type`, `entry_options`, `allow_add`, `allow_delete`, `prototype`
- rendu récursif des noms HTML (`invoice[customer][name]`)
- support des fieldsets imbriqués
- field types legacy disponibles dans `src/Domain/Field`
- thème HTML par défaut + Bootstrap/Tailwind
