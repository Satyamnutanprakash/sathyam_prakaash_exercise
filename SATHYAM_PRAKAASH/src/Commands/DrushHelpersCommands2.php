<?php

namespace Drupal\sathyam_prakaash\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class DrushHelpersCommands extends DrushCommands {

  /**
   * @var Drupal\Core\Entity\EntityTypeManagerInterface $entityQuery
   *    Entity query service.
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
   * @return \Consolidation\OutputFormatters\StructuredData\RowsOfFields
   *   The list of article titles.
   */
  public function getTitleArticle() {
    $query = $this->entityQuery->getStorage('node')->getQuery(); // Load the Entity Query service.
    $query->accessCheck(FALSE); // Disable access checks for the query.
    $entityIds = $query->condition('type', 'article') // Specify the entity type you want to query (e.g., node, user, taxonomy_term).
                       ->condition('status', 1)  // Add additional conditions if needed.
                       ->sort('created', 'DESC')
                       ->range(0, 10)  // Set the range to retrieve only 10 entities.
                       ->execute(); // Execute the query and get the result.
    $entities = $this->entityQuery->getStorage('node')->loadMultiple($entityIds); // Load the entities based on the returned IDs.

    $titles = [];
    // Loop through the entities and retrieve their titles.
    foreach ($entities as $entity) {
      $title = $entity->label();
      $titles[] = ['title' => $title];
    }

    return new RowsOfFields($titles);
  }
}