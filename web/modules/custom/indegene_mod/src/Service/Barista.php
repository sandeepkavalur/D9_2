<?php

namespace Drupal\indegene_mod\Service;

use Drupal\Core\Database\Connection;

class Barista {

  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public function setData() {
    # code...
    return 'hello this is Barista class funtion!!!';
  }
}
