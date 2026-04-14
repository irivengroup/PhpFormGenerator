## Unreleased

### Changed
- CSRF protection is now enabled by default for factory-created and builder-based forms.
- Users only need to declare `csrf_protection => false` when they intentionally want to disable CSRF.
- Documentation updated to describe the default CSRF behavior and current application form types.
