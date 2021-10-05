<?php

namespace Drupal\indegene_mod\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'Spacex_default' formatter.
 *
 * @FieldFormatter(
 *   id = "Spacex_default",
 *   label = @Translation("Spacex text"),
 *   field_types = {
 *     "Random"
 *   }
 * )
 */
class SpacexFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the random string.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = ['#markup' => $item->value];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Declare a setting named 'text_length', with
      // a default value of 'short'
      'text_length' => 'short',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['text_length'] = [
      '#title' => $this->t('Text length'),
      '#type' => 'select',
      '#options' => [
        'short' => $this->t('Short'),
        'long' => $this->t('Long'),
      ],
      '#default_value' => $this->getSetting('text_length'),
    ];

    return $form;
  }

}
