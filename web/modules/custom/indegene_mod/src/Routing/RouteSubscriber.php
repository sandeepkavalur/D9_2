<?php

namespace Drupal\indegene_mod\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change path '/user/login' to '/login'.
    if ($route = $collection->get('indegene_mod.my_page2')) {
      $route->setPath('/dummy');
      // return new RedirectResponse(\Drupal\Core\Url::fromRoute('indegene_mod.myform')->toString());

    }
  }
}
