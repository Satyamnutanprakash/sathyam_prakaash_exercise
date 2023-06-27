<?php

namespace Drupal\sathyam_prakaash\Controller;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\node\Entity\Node;
class DNodeAccess {

  /**
   * Function.
   */

  public function accessNode(AccountInterface $account, $node) {
    $node = Node::load($node);
    $type_id = $node->bundle();
    if ($account->hasPermission("dynamic $type_id content")) {
      $result = AccessResult::allowed();
    }
    else {
      $result = AccessResult::forbidden();
    }

    $result->addCacheableDependency($node);

    return $result;
  }

}