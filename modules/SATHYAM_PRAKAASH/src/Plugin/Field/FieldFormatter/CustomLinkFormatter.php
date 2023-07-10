<?php

namespace Drupal\sathyam_prakaash\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Url;

/**
 * Define the "custom link formatter".
 *
 * @FieldFormatter(
 *   id = "custom_link_formatter",
 *   label = @Translation("custom link formatter"),
 *   description = @Translation("Desc for custom link formatter"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class CustomLinkFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      $title = $entity->label();
      $url = $entity->toUrl()->setAbsolute();

      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => $this->t('@title | <a href="@url">@url</a>', [
          '@title' => $title,
          '@url' => $url->toString(),
        ]),
      ];
    }

    return $elements;
  }

}
