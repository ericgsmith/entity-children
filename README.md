# Entity Children

This is a service used to find the child entities of the current menu item.

## Usage

This just provides the service, and does not provide any display implementation.

Fetch the list of entities:

```php
$entities = \Drupal::service('entity_children.menu_entity_searcher')->getChildEntities();
```

