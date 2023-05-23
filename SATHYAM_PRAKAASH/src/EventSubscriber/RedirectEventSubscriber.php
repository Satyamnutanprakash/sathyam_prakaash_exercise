<?php

namespace Drupal\sathyam_prakaash\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RedirectEventSubscriber
 * @package Drupal\sathyam_prakaash\EventSubscriber
 *
 * Redirects '/node/10' to '/node/3'
 */
class RedirectEventSubscriber implements EventSubscriberInterface {

  public function checkForRedirection(RequestEvent $event) {

    $request = $event->getRequest();
    $path = $request->getRequestUri();
    if(strpos($path, '/node/10') !== false) {
      // Redirect old  urls
      $new_url = str_replace('/node/10','/node/3', $path);
      $new_response = new RedirectResponse($new_url, '301');
      $new_response->send();
    }

    // This is necessary because this also gets called on
    // node sub-tabs such as "edit", "revisions", etc.  This
    // prevents those pages from redirected.
    if ($request->attributes->get('_route') !== 'entity.node.canonical') {
      return;
    }

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    //The dynamic cache subscribes an event with priority 27. If you want that your code runs before that you have to use a priority >27:
    $events[KernelEvents::REQUEST][] = array('checkForRedirection', 29);
    return $events;
  }

}