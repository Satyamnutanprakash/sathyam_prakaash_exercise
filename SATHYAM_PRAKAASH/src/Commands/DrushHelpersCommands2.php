<?php

namespace Drupal\custom_drush\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class DrushHelpersCommands extends DrushCommands {

  /**
   * @var Drupal\Core\Entity\EntityTypeManagerInterface $entityQuery
   *    Entity query service.
   */

  public function __construct(EntityTypeManagerInterface $entityQuery) {
    $this->entityQuery = $entityQuery;
    parent::__construct();
  }

  /**
   * Command that returns a list of all active-users.
   *
   * @field-labels
   *  title: Title
   *
   * @usage drush-helpers:title-article
   *   Returns all the title of articles
   *
   * @command drush title-article
   * @aliases title-article
   *
   */

    public function getTitle() {
      $query = \Drupal::entityQuery('node'); // Load the Entity Query service.
      $entityIds = $query->condition('type', 'article') // Specify the entity type you want to query (e.g., node, user, taxonomy_term).
                         ->condition('status', 1)  // Add additional conditions if needed.
                         ->sort('created', 'DESC')
                         ->range(0, 10);  // Set the range to retrieve only 10 entities.
      $entityIds = $query->execute(); // Execute the query and get the result.
      $entities = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($entityIds); // Load the entities based on the returned IDs.

      $titles = [];
      // Loop through the entities and retrieve their titles.
      foreach ($entities as $entity) {
        $title = $entity->label();
        $titles[] = $title;
      }
      return $titles;
    }
}