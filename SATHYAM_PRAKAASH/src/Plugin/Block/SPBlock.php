<?php

  namespace Drupal\sathyam_prakaash\Plugin\Block;
  use Drupal\Core\Block\BlockBase;

 /**
  * Provides simple block for SPBlock.
  * @Block (
  * id = "sp_plugin_block",
  * admin_label = "SP Block"
  * )
  */

class SPBlock extends BlockBase {

  public function build() { //rendering function
    // Create a new instance of your form.
    $form = \Drupal::formBuilder()->getForm('\Drupal\sathyam_prakaash\Form\ModifiedForm');

    // Render the form using drupal_render().
    $render_array = [
      'form' => $form,
    ];
    $output = \Drupal::service('renderer')->renderRoot($render_array);
    return [
      '#markup' => $output,
    ];
  }

}