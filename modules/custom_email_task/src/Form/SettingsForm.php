<?php

namespace Drupal\custom_email_task\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;

/**
 * Configure CUstom Email Task Module settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_email_task_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['custom_email_task.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_email_task.settings');

    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Subject'),
      '#default_value' => $config->get('subject'),
      '#required' => TRUE,
    ];

    $format = 'basic_html';
    if ($this->config('custom_email_task.settings')->get('message')['format']) {
      $format = $this->config('custom_email_task.settings')->get('message')['format'];
    }

    $form['message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Email Message'),
      '#format' => $format,
      '#default_value' => $this->config('custom_email_task.settings')->get('message')['value'],
      '#required' => TRUE,
    ];

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
    $config = $this->config('custom_email_task.settings');
    $config->set('subject', $form_state->getValue('subject'));
    $config->set('message', $form_state->getValue('message'));
    $config->save();
    parent::submitForm($form, $form_state);
    // $form_state->setRedirect('<front>');
    // tempstore alternative of Session.
    }

}
