<?php

namespace Drupal\indegene_mod\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a CustomWebService Resource
 *
 * @RestResource(
 *   id = "custom_web_service_resource",
 *   label = @Translation("CustomWebService Resource"),
 *   uri_paths = {
 *     "canonical" = "/custom_web_service/get_response"
 *   }
 * )
 */
class CustomWebService extends ResourceBase {
  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {

    $userStorage = \Drupal::entityTypeManager()->getStorage('user');

    $query = $userStorage->getQuery();
    $uids = $query
      ->condition('status', '1')
      ->condition('roles', 'employee')
      ->execute();

    $users = $userStorage->loadMultiple($uids);
    foreach($users as $user){
      $username = $user->get('name')->getString();
      $mail =  $user->get('mail')->getString();
      $userlist[$mail] = $username;
      // kint($userlist);
    }
    // kint($users);die;

    // $response = ['message' => 'Hello, this is a testing rest service'];
    return new ResourceResponse($userlist);
  }
}
