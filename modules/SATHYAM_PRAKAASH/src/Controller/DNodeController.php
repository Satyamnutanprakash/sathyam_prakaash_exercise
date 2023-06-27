<?php

namespace Drupal\sathyam_prakaash\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Function.
 */
class DNodeController extends ControllerBase {

  /**
   * Function.
   */
  public function nodeTitle(Node $node) {
    if (!empty($node)) {
      $title = $node->getTitle();
      return [
        '#markup' => $title,
      ];
    }
    else {
      throw new NotFoundHttpException();
    }
  }

  /**
   * Function.
   */
  public function nodeDTitleLoad(Node $node) {
    $prepend_text = "Node of ";
    return $prepend_text . $node->getTitle();
  }

}
