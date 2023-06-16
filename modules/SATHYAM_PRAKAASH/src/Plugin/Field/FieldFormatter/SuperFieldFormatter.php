<?php

namespace Drupal\sathyam_prakaash\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Define the "super field formatter".
 *
 * @FieldFormatter(
 *   id = "super_field_formatter",
 *   label = @Translation("Super Field Formatter"),
 *   description = @Translation("Desc for Super Field Formatter"),
 *   field_types = {
 *     "super_field_type"
 *   }
 * )
 */
class SuperFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'critic' => 'Film criticism ',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['critic'] = [
      '#type' => 'textfield',
      '#title' => 'Film criticism ',
      '#default_value' => $this->getSetting('critic'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t("Film criticism : @critic", ["@critic" => $this->getSetting('critic')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#markup' => $this->getSetting('critic') . $item->value,
      ];
    }
    return $element;
  }

}
