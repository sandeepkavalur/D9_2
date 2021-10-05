<?php
namespace Drupal\indegene_mod\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\indegene_mod\Service\Barista;

/**
 * Provides route responses for the Example module.
 */
class controller1 extends ControllerBase {

  protected $loaddata;

  public function __construct() {
    $this->loaddata = \Drupal::service('indegene_mod.barista');
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function myPage($args) {
    $user = \Drupal::currentUser()->getAccountName();

    echo mynewmethod();

    // kint($user); die;

    // $myvar1 =  $this->getarticles();
    // kint($myvar1);

    return [
      '#markup' => 'My first page in custom module ---------------- ' . $args,
      '#theme' => 'indegene_mod_theme_hook',
      '#variable1' => $args,
      '#variable2' => [1, 2, 3],
      '#date' => date('d-m-Y'),
      '#myusername' => $user,
    ];
  }

  public function myPage2() {
    return [
      '#markup' => t('My second page in custom module ---------------- ' . date('d-m-Y') . '-----------' . $this->loaddata->setData() ),
    ];
  }

  public function getarticles(){
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'page');
    $entity_ids = $query->execute();

    return ($entity_ids);
  }
}
