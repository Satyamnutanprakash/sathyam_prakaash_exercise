<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the example form.
 */
class AddressForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'address_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#attached']['library'][] = "sathyam_prakaash/css_lib";

    $form['permanent_address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Permanent Address'),
      '#attributes' => ['id' => 'permanent-address'],
    ];

    $form['same_as_permanent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Same as Permanent'),
      '#attributes' => ['id' => 'same-as-permanent'],
    ];

    $form['temporary_address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Temporary Address'),
      '#attributes' => ['id' => 'temporary-address'],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Save the configuration',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}