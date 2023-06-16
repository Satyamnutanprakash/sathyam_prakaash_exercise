<?php

namespace Drupal\sathyam_prakaash\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Define the "super field type".
 *
 * @FieldType(
 *   id = "super_field_type",
 *   label = @Translation("Super Field Type"),
 *   description = @Translation("Desc for Super Field Type"),
 *   category = @Translation("Text"),
 *   default_widget = "super_field_widget",
 *   default_formatter = "super_field_formatter",
 * )
 */
class SuperFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => $field_definition->getSetting("length"),
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'length' => 255,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = [];

    $element['length'] = [
      '#type' => 'number',
      '#title' => t("Length of your text"),
      '#required' => TRUE,
      '#default_value' => $this->getSetting("length"),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'info' => "Rocking Movie",
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    $element['info'] = [
      '#type' => 'textfield',
      '#title' => 'Movie Ratings Extended',
      '#required' => TRUE,
      '#default_value' => $this->getSetting("info"),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')->setLabel(t("Name"));

    return $properties;
  }

}
