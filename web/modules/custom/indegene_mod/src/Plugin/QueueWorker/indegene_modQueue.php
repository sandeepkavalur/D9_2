<?php

namespace Drupal\indegene_mod\Plugin\QueueWorker;

use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Processes Node Tasks.
 *
 * @QueueWorker(
 *   id = "indegene_mod_queue",
 *   title = @Translation("Indegene Create User Worker: csv"),
 *   cron = {"time" = 30}
 * )
 */
class indegene_modQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($val) {
    echo "hi";
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
}
