<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the token form.
 */
class TokenForm extends ConfigFormBase {
  const CONFIGNAME = "sathyam_prakaash.settings";

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'token_form';
  }

  /**
   * Function.
   */
  protected function getEditableConfigNames() {
    return [
      static::CONFIGNAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIGNAME);
    $form['helptext'] = [
      '#type' => 'textfield',
      '#title' => 'Help Text',
      '#default_value' => $config->get("helptext"),
    ];

    // Token support.
    if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['tokens'] = [
        '#title' => $this->t('Tokens'),
        '#type' => 'container',
      ];
      $form['tokens']['help'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => [
          'node',
          'site',
        ],
        '#global_types' => FALSE,
        '#dialog' => TRUE,
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIGNAME);
    $config->set("helptext", $form_state->getValue('helptext'));
    $config->save();
  }

}