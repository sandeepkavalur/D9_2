<?php

namespace Drupal\indegene_mod\Form;

use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\File\FileSystemInterface;

/**
 * Class CreateNodeForm.
 *
 * @package Drupal\indegene_mod\Form
 */
class CreateNodeForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'create_node_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file_upload'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Import CSV file'),
      '#description' => $this->t('The CSV file to be imported. '),
       '#autoupload' => TRUE,
       '#upload_validators' => ['file_validate_extensions' => ['csv']],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $validators = ['file_validate_extensions' => ['csv']];
    // if ($file = file_save_upload('file_upload', $validators, FALSE, 0, FileSystemInterface::EXISTS_REPLACE)) {
    //   $data = fopen($file->getFileUri(), "r");
    //   $row = fgetcsv($data, 1000, ',');
    // }


    $file_upload = $form_state->getValue('file_upload');

    $file = File::load($file_upload[0]);
    $handle = fopen($file->getFileUri(), 'r');
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
    // kint($data);die;

    $batch = array(
      'title' => t('Creating Node...'),
      'operations' => array(
        array(
          '\Drupal\indegene_mod\CreateNode::createNodeBatch',
          [$data]
        ),
      ),
      'finished' => '\Drupal\indegene_mod\CreateNode::createNodeBatchFinishedCallback',
    );

    batch_set($batch);
  }

}
