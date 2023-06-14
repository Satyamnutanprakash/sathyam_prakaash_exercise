<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Dependent Dropdown Form.
 */
class DropdownForm extends FormBase {

  /**
   * Database connection object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a DropdownForm object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection object.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dropdown_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $opt = static::getCountry();
    $country = $form_state->getValue('country');

    // exit;.
    $state = $form_state->getValue('state');
    // print_r($state);
    $form['country'] = [
      '#type' => 'select',
      '#title' => 'Country',
      '#options' => $opt,
      '#empty_option' => '- Select -',
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::dropdownCallback',
        'wrapper' => 'state-wrapper',
        'event' => 'change',
      ],
    ];

    $form['state'] = [
      '#type' => 'select',
      '#title' => 'State',
      '#options' => static::getStates($country),
      '#empty_option' => '- Select -',
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::dropdownCallback',
        'wrapper' => 'district-wrapper',
        'event' => 'change',
      ],
      '#prefix' => '<div id="state-wrapper">',
      '#suffix' => '</div>',
    ];

    $form['district'] = [
      '#type' => 'select',
      '#title' => 'District',
      '#options' => static::getDistricts($state),
      '#empty_option' => '- Select -',
      '#required' => TRUE,
      '#prefix' => '<div id="district-wrapper">',
      '#suffix' => '</div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Process the form submission.
    $trigger = (string) $form_state->getTriggeringElement()['#value'];
    if ($trigger != 'submit') {
      $form_state->setRebuild();
    }
  }

  /**
   * Ajax callback to update the state dropdown.
   */
  public function dropdownCallback(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $triggering_element_name = $triggering_element['#name'];
    if ($triggering_element_name === 'country') {
      // Lists the state for the particular country.
      return $form['state'];
    }
    elseif ($triggering_element_name === 'state') {
      // Lists the state for the particular country.
      return $form['district'];
    }
  }

  /**
   * Get the country options for the select element.
   */
  public function getCountry() {
    $query = $this->database->select('country', 'c');
    $query->fields('c', ['country_id', 'country_name']);
    $result = $query->execute()->fetchAll();
    $options = [];
    foreach ($result as $row) {
      $options[$row->country_id] = $row->country_name;
    }
    return $options;
  }

  /**
   * Get the state options for the select element.
   */
  public function getStates($country) {
    $query = $this->database->select('state', 's');
    $query->fields('s', ['state_id', 'state_name']);
    $query->condition('s.country_id', $country);
    $result = $query->execute()->fetchAll();
    $options = [];
    foreach ($result as $row) {
      $options[$row->state_id] = $row->state_name;
    }
    return $options;
  }

  /**
   * Get the district options for the select element.
   */
  public function getDistricts($state) {
    $query = $this->database->select('district', 'd');
    $query->fields('d', ['district_id', 'district_name']);
    $query->condition('d.state_id', $state);
    $result = $query->execute()->fetchAll();
    $options = [];
    foreach ($result as $row) {
      $options[$row->district_id] = $row->district_name;
    }
    return $options;
  }

}
