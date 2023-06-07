<?php

namespace Drupal\sathyam_prakaash\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\user\UserInterface;

/**
 * Event that is called when a user log in.
 */
class UserLoginEvent extends Event {

  const EVENT_NAME = 'custom_events_user_login';

  /**
   * The user account.
   *
   * @var \Drupal\user\UserInterface
   */
  public $account;

  /**
   * Constructs the object.
   *
   * @param \Drupal\user\UserInterface $account
   *   The account of the user logged in.
   */
  public function __construct(UserInterface $account) {
    // Will return the user account.
    $this->account = $account;
  }

}
