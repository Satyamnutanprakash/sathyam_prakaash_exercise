<?php

namespace Drupal\sathyam_prakaash;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\Entity\NodeType;

/**
 * Module permissions.
 */
class DNodePermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of permissions.
   *
   * @return array
   *   The permissions.
   *
   * @see \Drupal\user\PermissionHandlerInterface::getPermissions()
   */
  public function nodeTypePermissions() {
    $perms = [];
    // Generate node permissions for all node types.
    foreach (NodeType::loadMultiple() as $type) {
      $type_id = $type->id();
      $type_params = ['%type' => $type->label()];
      $perms += [
        "dynamic $type_id content" => [
          'title' => $this->t('%type: dynamic content', $type_params),
        ],
      ];
    }
    return $perms;
  }

}
