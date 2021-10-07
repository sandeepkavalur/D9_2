<?php

namespace Drupal\indegene_mod\Commands;

use Drupal\user\Entity\User;
use Drush\Commands\DrushCommands;

/**
 * A drush command file.
 *
 * @package Drupal\drush9_custom_commands\Commands
 */
class Drush9CustomCommands extends DrushCommands {

  /**
   * Drush command that import csv file and creates.
   *
   * @param string $filename
   *   Argument with message to be displayed.
   * @command indegene_mod:importcsv
   * @aliases im-csv
   *
   * @usage indegene_mod:im-csv filename
   */
  public function importcsv($filename) {
    $handle = fopen(\Drupal::root() . '\modules\custom\indegene_mod\\'.$filename, 'r');
    $key = 0;
    $data = [];

    while(($row = fgetcsv($handle, 1000, ',')) !== false) {
      if(user_load_by_mail($row[5])){
        continue;
      }
      else {
        $data['user'][$key]['FirstName'] = $row[0];
        $data['user'][$key]['LastName'] = $row[1];
        $data['user'][$key]['UserName'] = $row[2];
        $data['user'][$key]['Gender'] = $row[3];
        $data['user'][$key]['Role'] = $row[4];
        $data['user'][$key]['Email'] = $row[5];
        $data['user'][$key]['Password'] = $row[6];
        $key++;
      }
    }

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

    // $batch = array(
    //   'title' => t('Creating Node...'),
    //   'operations' => array(
    //     array(
    //       '\Drupal\indegene_mod\CreateNode::createNodeBatch',
    //       [$data]
    //     ),
    //   ),
    //   'finished' => '\Drupal\indegene_mod\CreateNode::createNodeBatchFinishedCallback',
    // );

    // batch_set($batch);

    $this->output()->writeln('custom drush command finished execution');
  }

}
