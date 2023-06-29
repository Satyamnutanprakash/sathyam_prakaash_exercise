<?php

namespace Drupal\sathyam_prakaash\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Function.
 */
class ModalController extends ControllerBase {

  /**
   * Function.
   */
  public function modalLink() {
    $build['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $build = [
      '#markup' => '<a href="/drupal-10.0.4/modified-form-page" class="use-ajax" data-dialog-type="modal">Click Here</a>',
    ];
    return $build;
  }
}