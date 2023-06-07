<?php

namespace Drupal\sathyam_prakaash\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Command to get editor role active users.
 */
class DrushHelpersCommands extends DrushCommands {

  /**
   * Using Entity manager service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityManager = $entityTypeManager;
    parent::__construct();
  }

  /**
   * Command that returns a list of all active-users.
   *
   * @field-labels
   *  id: User Id
   *  name: User Name
   *  email: User Email
   * @default-fields id,name,email
   *
   * @usage drush-helpers:active-users
   *   Returns all active-users
   *
   * @command drush active-users:editor
   * @aliases role-editor
   *
   * @return RowOfFields
   *   with user id, name and email.
   */
  public function blockedUsers() {
    $users = $this->entityManager->getStorage('user')->loadByProperties(['status' => 1]);
    $rows = [];
    foreach ($users as $user) {
      if ($user->hasRole('editor')) {
        $rows[] = [
          'id' => $user->id(),
          'name' => $user->name->value,
          'email' => $user->mail->value,
        ];
      }
    }
    return new RowsOfFields($rows);
  }

}
