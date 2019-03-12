<?php

namespace Drupal\entity_children;

use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class MenuEntitySearcher.
 */
class MenuEntitySearcher implements MenuEntitySearcherInterface {

  /**
   * Drupal\Core\Menu\MenuLinkTreeInterface definition.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * MenuEntitySearcher constructor.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_link_tree
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(MenuLinkTreeInterface $menu_link_tree, EntityTypeManagerInterface $entity_type_manager) {
    $this->menuLinkTree = $menu_link_tree;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getChildEntities($entityTypeId = 'node', $menuName = 'main') {
    $parameters = $this->getMenuTreeParams($menuName);

    $tree = $this->menuLinkTree->load($menuName, $parameters);

    $manipulators = $this->getMenuManipulators();
    $tree = $this->menuLinkTree->transform($tree, $manipulators);

    $entityIds = [];

    foreach ($tree as $link) {
      $params = $link->link->getRouteParameters();
      if (empty($params[$entityTypeId])) {
        continue;
      }

      $entityIds[] = $params[$entityTypeId];
    }

    return $entityIds ? $this->entityTypeManager->getStorage($entityTypeId)->loadMultiple($entityIds) : [];
  }

  /**
   * Get the tree params to use for the menu.
   *
   * @param string $menuName
   *   The menu to look in for the children.
   *
   * @return \Drupal\Core\Menu\MenuTreeParameters
   *   Params to use when loading the tree.
   */
  protected function getMenuTreeParams($menuName) {
    $parameters = $this->menuLinkTree->getCurrentRouteMenuTreeParameters($menuName);
    $parameters
      ->setRoot(current($parameters->activeTrail))
      ->excludeRoot()
      ->onlyEnabledLinks();
    return $parameters;
}

  /**
   * Get the list of menu manipulators to apply to the tree before loading the children.
   *
   * @return array
   */
  protected function getMenuManipulators() {
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    return $manipulators;
  }
}
