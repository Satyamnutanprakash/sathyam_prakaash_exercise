<?php

namespace Drupal\sathyam_prakaash\EventSubscriber;

// To use the created custom Event.
use Drupal\sathyam_prakaash\Event\UserLoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserLoginSubscriber.
 *
 * @package Drupal\sathyam_prakaash\EventSubscriber
 */
class UserLoginDemo implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      // Static class constant => method on this class.
      UserLoginEvent::EVENT_NAME => 'onUserLogin',
    ];
  }

  /**
   * Subscribe to the user login event dispatched.
   *
   * @param \Drupal\sathyam_prakaash\Event\UserLoginEvent $event
   *   Our custom general object.
   */
  public function onUserLogin(UserLoginEvent $event) {
    // Using the service of database.
    $database = \Drupal::database();
    // Using the service to get date.formatter.
    $dateFormatter = \Drupal::service('date.formatter');

    // Selecting the table from db.
    $account_created = $database->select('users_field_data', 'ud')
    // Returns when the account was created.
      ->fields('ud', ['created'])
    // Returns the userid.
      ->condition('ud.uid', $event->account->id())
      ->execute()
      ->fetchField();
    // Using message service to get message whenever user logs in.
    \Drupal::messenger()->addStatus(t('Welcome to the site, your account was created on %created_date.', [
      '%created_date' => $dateFormatter->format($account_created, 'long'),
    ]));
  }

}
