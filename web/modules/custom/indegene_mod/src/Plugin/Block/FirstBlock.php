<?php

namespace Drupal\indegene_mod\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides a 'Custom' block.
 *
 * @Block(
 *   id = "first_custom_block",
 *   admin_label = @Translation("First custom block in my module"),
 *   category = @Translation("First Block Type"),
 * )
 */

class FirstBlock extends BlockBase implements BlockPluginInterface {

/**
 *{@inheritDoc}
 */

  public function build() {
    $config =\Drupal::config('indegene_mod.settings');
    $form1 =\Drupal::formBuilder()->getForm('Drupal\indegene_mod\Form\form1');
    $output = 'Output coming from block twig';
    return [
      '#markup' => 'First custom block text!!!',
      $form1,

      // '#theme' => 'custom-block',
      // '#data' => $form1,
    ];
  }
}
