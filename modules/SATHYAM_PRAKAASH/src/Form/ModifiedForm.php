<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    $form['#attached']['library'][] = "sathyam_prakaash/css_lib";

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
    $form['mobile'] = [
      '#type' => 'tel',
      '#title' => 'Mobile No.',
      '#required' => TRUE,
      '#placeholder' => '1234567890',
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => 'Email',
      '#placeholder' => 'abc@gmail.com',
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
   * Invoke command function.
   */
  public function ajaxSubmit() {
    $response = new AjaxResponse();
    $response->addCommand(new InvokeCommand("html", 'testing'));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ((strlen($form_state->getValue('mobile')) < 10) OR (strlen($form_state->getValue('mobile')) > 10))
    {
      $form_state->setErrorByName('mobile', $this->t('The Mobile number must be of 10 digit'));
    }
    $email = $form_state->getValue('email');
    if (empty($email)) {
      $form_state->setErrorByName('email', $this->t('The email field is required'));
    }
    else {
      if (!preg_match('/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/', $email)) {
        $form_state->setErrorByName('email', $this->t('Enter a valid email example : luciferosid@gmail.com'));
      }
    }
  }

  /**
   * Submit form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger->addMessage("Your Details Submitted Successfully");
    $this->database->insert("form_details")->fields([
      'firstname' => $form_state->getValue("firstname"),
      'lastname' => $form_state->getValue("lastname"),
      'mobile' => $form_state->getValue("mobile"),
      'email' => $form_state->getValue("email"),
      'gender' => $form_state->getValue("gender"),
    ])->execute();

    $form_state->setRedirect('system.admin_content');
  }
}
