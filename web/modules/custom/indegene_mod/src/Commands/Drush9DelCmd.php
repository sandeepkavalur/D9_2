<?php

namespace Drupal\indegene_mod\Commands;

use Drupal\user\Entity\User;
use Drush\Commands\DrushCommands;

/**
 * A drush command file.
 *
 * @package Drupal\drush9_custom_commands\Commands
 */
class Drush9DelCmd extends DrushCommands {

  /**
   * Drush command that import csv file and creates users.
   *
   * @param string $deluser
   *   Argument with message to be displayed.
   * @command indegene_mod:deluser
   * @aliases del-user
   *
   * @usage indegene_mod:del-user filename
   */
  public function importcsv($deluser) {
    $data = [];
    // $user = User::load(\Drupal::currentUser()->id());
    // user_load_by_name($user);
    // user_delete(array(), $uid);


    $users = \Drupal::entityTypeManager()->getStorage('user')
      ->loadByProperties(['mail' => $deluser ]);
    // echo '<pre>';
    // print_r($users);die;
    $users = reset($users);
    if ($users) {
      $uid = $users->id();
      $user = User::load($uid);
      $user->delete();
    }

    $this->output()->writeln('custom drush command finished execution');
  }

}
