<?php

namespace Drupal\sathyam_prakaash\EventSubscriber;
use Drupal\sathyam_prakaash\Event\UserLoginEvent; //to use the created custom Event
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
    $database = \Drupal::database(); //using the service of database
    $dateFormatter = \Drupal::service('date.formatter'); //using the service to get date.formatter

    $account_created = $database->select('users_field_data', 'ud') //selecting the table from db
      ->fields('ud', ['created'])  //returns when the account was created
      ->condition('ud.uid', $event->account->id())  //returns the userid
      ->execute()
      ->fetchField();
    //using message service to get message whenever user logs in
    \Drupal::messenger()->addStatus(t('Welcome to the site, your account was created on %created_date.', [
      '%created_date' => $dateFormatter->format($account_created, 'long'),
    ]));
  }

}