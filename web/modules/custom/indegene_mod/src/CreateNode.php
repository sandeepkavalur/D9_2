<?php

namespace Drupal\indegene_mod;


use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

class CreateNode {

  public static function createNodeBatch($data, &$context){
    kint($data['user']);die;
    $message = 'Creating Node...';
    $results = array();

    foreach($data['user'] as $key => $val){
      // Create user object.
      $user = User::create();

      //Mandatory settings
      $user->setPassword($val['Password']);
      $user->enforceIsNew();
      $user->setEmail($val['Email']);
      $user->setUsername($val['UserName']); //This username must be unique and accept only a-Z,0-9, - _ @ .
      $user->addRole($val['Role']); //E.g: authenticated
      //custom fields
      $user->set("field_first_name", $val['FirstName']);
      $user->set("field_last_name", $val['LastName']);
      $user->set("field_gender", $val['Gender']);

      $user->activate();
      $results[] = $user->save();
    }

    $context['message'] = $message;
    $context['results'] = $results;
  }

  public static function createNodeBatchFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One post processed.', '@count posts processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()
    ->addMessage($message);
  }
}
