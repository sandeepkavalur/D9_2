<?php

namespace Drupal\mymodule;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class DynamicPermissions
 * @package Drupal\mymodule
 */
class DynamicPermissions
{

  use StringTranslationTrait;

  /**
   * @return array
   */
  public function permissions()
  {
    $permissions = [];

    $count = 1;
    while ($count <= 5) {
      $permissions += [
        "indegene_mod permission $count" => [
          'title' => $this->t('indegene_mod permission @number', ['@number' => $count]),
          'description' => $this->t('This is a sample permission generated dynamically.'),
          'restrict access' => $count == 2 ? true : false,
        ],
      ];
      $count++;
    }
    return $permissions;
  }

}
