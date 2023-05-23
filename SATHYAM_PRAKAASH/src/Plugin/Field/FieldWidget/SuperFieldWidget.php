<?php

namespace Drupal\sathyam_prakaash\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Define the "super field type".
 *
 * @FieldWidget(
 *   id = "super_field_widget",
 *   label = @Translation("Super Field Widget"),
 *   description = @Translation("Desc for Super Field Widget"),
 *   field_types = {
 *     "super_field_type"
 *   }
 * )
 */

class SuperFieldWidget extends WidgetBase {

    /**
     * {@inheritdoc}
     */

     public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
        $value = isset($items[$delta]->value) ? $items[$delta]->value : "";
        $element = $element + [
            '#type' => 'textfield',
            '#suffix' => "<span>" . $this->getFieldSetting("info") . "</span>",
            '#default_value' => $value,
            '#attributes' => [
                'ratings' => $this->getSetting('ratings'),
            ],
        ];
        return ['value' => $element];
     }

     /**
      * {@inheritdoc}
      */
      public static function defaultSettings() {
        return [
            'ratings' => 'Super Movie',
        ] + parent::defaultSettings();
      }

      /**
       * {@inheritdoc}
       */
      public function settingsForm(array $form, FormStateInterface $form_state) {
        $element['ratings'] = [
            '#type' => 'textfield',
            '#title' => 'Ratings',
            '#default_value' => $this->getSetting('ratings'),
        ];
        return $element;
      }

      /**
       * {@inheritdoc}
       */
      public function settingsSummary() {
        $summary = [];
        $summary[] = $this->t("Ratings : @ratings", array("@ratings" => $this->getSetting("ratings")));
        return $summary;
      }


}