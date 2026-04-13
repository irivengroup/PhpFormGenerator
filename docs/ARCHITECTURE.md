# Architecture V3

## Couches

- `Application` : orchestration et factories
- `Domain` : contrats, formes, champs, contraintes, validation, value objects
- `Infrastructure` : requêtes, mapping, CSRF
- `Presentation` : vues, rendu HTML, thèmes

## Pipeline

1. construction du formulaire via `FormBuilder`
2. soumission via `handleRequest()`
3. extraction des données requête
4. mapping vers tableau ou objet
5. validation champ par champ
6. création de `FormView`
7. rendu HTML via `HtmlRenderer`
