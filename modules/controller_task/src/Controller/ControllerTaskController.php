<?php

namespace Drupal\controller_task\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Returns responses for Custom Controller Task Module routes.
 */
class ControllerTaskController extends ControllerBase {

  /**
   * Builds the response.
   */

  public function build(NodeInterface $node) {
    if (!$this->currentUser()->hasPermission('administer custom node details')) {
      throw new AccessDeniedHttpException();
    }

    $form = $this->formBuilder()->getForm('Drupal\controller_task\Form\NodeDetailsForm', $node);

    return $form;
  }

}
