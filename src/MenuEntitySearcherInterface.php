<?php

namespace Drupal\entity_children;

/**
 * Interface MenuEntitySearcherInterface.
 */
interface MenuEntitySearcherInterface {

  /**
   * Get the children entities from the current route.
   *
   * @param string $entityTypeId
   *   The entity type id, eg. 'node'
   *
   * @param string $menuName
   *   The menu to look in for the children.
   *
   * @return array|\Drupal\Core\Entity\EntityInterface[]
   *   List of entities found.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getChildEntities($entityTypeId = 'node', $menuName = 'main');

}
