<?php

namespace Drupal\sathyam_prakaash\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Command to get latest 10 article contents.
 */
class DrushHelpersCommands2 extends DrushCommands {

  /**
   * Using Entity query service.
   *
   * @var entityQueryUsingEntityqueryservice
   */

  protected $entityQuery;

  /**
   * Constructs a new DrushHelpersCommands object.
   *
   * @param Drupal\Core\Entity\EntityTypeManagerInterface $entityQuery
   *   The entity query service.
   */
  public function __construct(EntityTypeManagerInterface $entityQuery) {
    $this->entityQuery = $entityQuery;
    parent::__construct();
  }

  /**
   * Command that returns a list of all article content titles.
   *
   * @command drush:title-article
   * @aliases title-article
   *
   * @return RowOfFields
   *   The list of article titles.
   */
  public function getTitleArticle() {
    // Load the Entity Query service.
    $query = $this->entityQuery->getStorage('node')->getQuery();
    // Disable access checks for the query.
    $query->accessCheck(FALSE);
    // Specify the entity type you want to query.
    // (e.g., node, user, taxonomy_term).
    $entityIds = $query->condition('type', 'article')
    // Add additional conditions if needed.
      ->condition('status', 1)
      ->sort('created', 'DESC')
    // Set the range to retrieve only 10 entities.
      ->range(0, 10)
    // Execute the query and get the result.
      ->execute();
    // Load the entities based on the returned IDs.
    $entities = $this->entityQuery->getStorage('node')->loadMultiple($entityIds);

    $titles = [];
    // Loop through the entities and retrieve their titles.
    foreach ($entities as $entity) {
      $title = $entity->label();
      $titles[] = ['title' => $title];
    }

    return new RowsOfFields($titles);
  }

}
