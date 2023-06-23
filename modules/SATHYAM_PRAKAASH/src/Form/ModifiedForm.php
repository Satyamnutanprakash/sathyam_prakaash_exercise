<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Config Form to store configuration values.
 */
class ModifiedForm extends FormBase {
  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;
  /**
   * The Database service.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs custom form. .
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Database\Connection $database
   *   The database service.
   */
  public function __construct(MessengerInterface $messenger, Connection $database) {
    $this->messenger = $messenger;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('database'),
    );
  }
  /**
   * Generated form id.
   */
  public function getFormId() {
    return 'modified_form_form_details';
  }

  /**
   * Build form generates form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['firstname'] = [
      '#type' => 'textfield',
      '#title' => 'First Name',
      '#required' => TRUE,
      '#placeholder' => 'First Name',
    ];
    $form['lastname'] = [
      '#type' => 'textfield',
      '#title' => 'Last Name',
      '#required' => FALSE,
      '#placeholder' => 'Last Name',
    ];
    $form['gender'] = [
      '#type' => 'select',
      '#title' => 'Gender',
      '#options' => [
        'male' => 'Male',
        'female' => 'Female',
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
    ];
    return $form;
  }

  /**
   * Submit form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger->addMessage("Your Details Submitted Successfully");
    $this->database->insert("form_details")->fields([
      'firstname' => $form_state->getValue("firstname"),
      'lastname' => $form_state->getValue("lastname"),
      'gender' => $form_state->getValue("gender"),
    ])->execute();
  }

}
