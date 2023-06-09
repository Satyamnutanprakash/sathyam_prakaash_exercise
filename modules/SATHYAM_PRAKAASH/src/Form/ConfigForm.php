<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Config Form to store configuration values.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * Settings Variable.
   */
  const CONFIGNAME = "sp_config_form.settings";

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "sp_config_form_settings";
  }

  /**
   * {@inheritdoc}
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
    $form['firstname'] = [
      '#type' => 'textfield',
      '#title' => '<span>First Name</span>',
      '#attached' => [
        'library' => [
          'sathyam_prakaash/css_lib',
        ],
      ],
      '#default_value' => $config->get("firstname"),
    ];

    $form['lastname'] = [
      '#type' => 'textfield',
      '#title' => 'Last Name',
      '#default_value' => $config->get("lastname"),
    ];

    $form['gender'] = [
      '#type' => 'textfield',
      '#title' => 'Gender',
      '#default_value' => $config->get("gender"),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIGNAME);
    $config->set("firstname", $form_state->getValue('firstname'));
    $config->set("lastname", $form_state->getValue('lastname'));
    $config->set("gender", $form_state->getValue('gender'));
    $config->save();
  }

}
