<?php

namespace Drupal\sathyam_prakaash\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Dropdown Form to store configuration values.
 */
class DropdownForm extends FormBase {

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
    $opt = static::country();
    // Initializing country variable.
    $country = $form_state->getValue('country') ?: 'none';
    // Initializing state variable.
    $state = $form_state->getValue('state') ?: 'none';

    // Country form.
    $form['country'] = [
      '#type' => 'select',
      '#title' => 'Country',
      '#options' => $opt,
      'default_value' => $country,
      '#ajax' => [
    // Ajax function.
        'callback' => '::dropdownCallback',
        'wrapper' => 'state-container',
        'event' => 'change',
      ],
    ];
    // Country form.
    $form['countrystates'] = [
      '#type' => 'select',
      '#title' => 'State',
      '#options' => static::countryStates($country),
      '#default_value' => !empty($form_state->getValue('countrystates')) ? $form_state->getValue('countrystates') : '',
      '#prefix' => '<div id="state-container">',
      '#suffix' => '</div>',
      '#ajax' => [
    // Ajax function.
        'callback' => '::dropdownCallback',
        'wrapper' => 'district-container',
        'event' => 'change',
      ],
    ];
    $form['statedistricts'] = [
      '#type' => 'select',
      '#title' => 'District',
      '#options' => static::stateDistricts($state),
      '#default_value' => !empty($form_state->getValue('statedistricts')) ? $form_state->getValue('statedistricts') : '',
      '#prefix' => '<div id="district-container">',
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
    $trigger = (string) $form_state->getTriggeringElement()['#value'];
    if ($trigger != 'submit') {
      $form_state->setRebuild();
    }
  }

  /**
   * Ajax callback function for state dropdown.
   */
  public function dropdownCallback(array &$form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $triggering_element_name = $triggering_element['#name'];
    if ($triggering_element_name === 'country') {
      // Lists the state for the particular country.
      return $form['countrystates'];
    }
    elseif ($triggering_element_name === 'state') {
      // Lists the state for the particular country.
      return $form['statedistricts'];
    }
  }

  /**
   * Function to get countries.
   */
  public function country() {
    return [
      'none' => '-none-',
      'India' => 'India',
      'United States' => 'United States',
      'Canada' => 'Canada',
      'Germany' => 'Germany',
    ];
  }

  /**
   * Functions to get states.
   */
  public function countryStates($country) {
    switch ($country) {
      case 'India':
        $opt = [
          'UP' => 'Uttar Pradesh',
          'GUJ' => 'Gujarat',
          'TN' => 'Tamil Nadu',
        ];
        break;

      case 'United States':
        $opt = [
          'CF' => 'California',
          'TS' => 'Texas',
          'FA' => 'Florida',
        ];
        break;

      case 'Canada':
        $opt = [
          'OT' => 'Ontario',
          'AB' => 'Alberta',
          'YK' => 'Yukon',
        ];
        break;

      case 'Germany':
        $opt = [
          'BV' => 'Bavaria',
          'SX' => 'Saxony',
          'HS' => 'Hessen',
        ];
        break;

      default:
        $opt = ['none' => '-none-'];
        break;
    }
    return $opt;
  }

  /**
   * Functions to get districts.
   */
  public function stateDistricts($state) {
    switch ($state) {
      case 'UP':
        $opt = [
          'Agra' => 'Agra',
          'Kanpur' => 'Kanpur',
        ];
        break;

      case 'GUJ':
        $opt = [
          'Ahmedabad' => 'Ahmedabad',
          'Surat' => 'Surat',
        ];
        break;

      case 'TN':
        $opt = [
          'Chennai' => 'Chennai',
          'Kanchipuram' => 'Kanchipuram',
        ];
        break;

      case 'CF':
        $opt = [
          'LAC' => 'Los Angeles County',
          'SFC' => 'San Francisco County',
        ];
        break;

      case 'TS':
        $opt = [
          'HC' => 'Harris County',
          'DC' => 'Dallas County',
        ];
        break;

      case 'FA':
        $opt = [
          'BC' => 'Bay County',
          'MDC' => 'Miami-Dade County',
        ];
        break;

      case 'OT':
        $opt = [
          'TO' => 'Town of Oakville',
          'CM' => 'City of Mississauga',
        ];
        break;

      case 'AB':
        $opt = [
          'PC' => 'Parkland County',
          'CC' => ' Clearwater County',
        ];
        break;

      case 'YK':
        $opt = [
          'WL' => 'Watson Lake',
          'WH' => 'Whitehorse',
        ];
        break;

      case 'BV':
        $opt = [
          'MN' => 'Munich ',
          'AG' => 'Augsburg',
        ];
        break;

      case 'SX':
        $opt = [
          'LP' => 'Leipzig',
          'CM' => 'Chemnitz',
        ];
        break;

      case 'HS':
        $opt = [
          'HR' => 'Hersfeld-Rotenburg',
          'WT' => 'Wetteraukreis',
        ];
        break;

      default:
        $opt = ['none' => '-none-'];
        break;
    }
    return $opt;
  }

}
