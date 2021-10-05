<?php

namespace Drupal\indegene_mod\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A widget spacex.
 *
 * @FieldWidget(
 *   id = "spacexwidget",
 *   label = @Translation("Spacex widget"),
 *   field_types = {
 *     "baz",
 *     "string"
 *   }
 * )
 */

 class SpacexWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = $element + [
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#size' => $this->getSetting('size'),
    ];

    return $element;
  }


  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Create the custom setting 'size', and
      // assign a default value of 60
      'size' => 60,
    ] + parent::defaultSettings();
  }


  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['size'] = [
      '#type' => 'number',
      '#title' => $this->t('Size of textfield'),
      '#default_value' => $this->getSetting('size'),
      '#required' => TRUE,
      '#min' => 1,
    ];

    return $element;
  }


  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Textfield size: @size', array('@size' => $this->getSetting('size')));

    return $summary;
  }
}
