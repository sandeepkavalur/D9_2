<?php

namespace Drupal\indegene_mod\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\user\Entity\User;

class IndegeneConfigForm extends ConfigFormBase{
  public function getFormId() {
    return 'indegene_mod_admin_settings';
  }

  protected function getEditableConfigNames() {
    return [
      'indegene_mod.settings',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('indegene_mod.settings');
    $form['default_value1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Indegene Setting 1'),
      '#default_value' => $config->get('default_value1'),
    ];
    $form['default_value2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Indegene Setting 2'),
      '#default_value' => $config->get('default_value2'),
    ];
    $form['default_value3'] = [
      '#type' => 'number',
      '#title' => $this->t('Indegene Setting 3'),
      '#default_value' => $config->get('default_value3'),
    ];

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

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit Configuration'),
    ];

    $form['#theme'] = 'custom-config-form';

    // return parent::buildForm($form, $form_state);
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $validators = ['file_validate_extensions' => ['csv']];
    if ($file = file_save_upload('file_upload', $validators, FALSE, 0, FileSystemInterface::EXISTS_REPLACE)) {
      // $data = file_get_contents($file->getFileUri());
      // \Drupal::messenger()->addStatus($data);

      $this->readcsvdata($file);
    }

    $this->config('indegene_mod.settings')
      ->set('default_value1', $form_state->getValue('default_value1'))
      ->set('default_value2', $form_state->getValue('default_value2'))
      ->set('default_value3', $form_state->getValue('default_value3'))
      ->save();
    parent::submitForm($form, $form_state);
  }

  public function readcsvdata(&$file){
    # code...
    $handle = fopen($file->getFileUri(), "r");
    $arr = array();
    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
      if(user_load_by_mail($row[5])){
        continue;
      }
      else {
        $arr= [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]];
        $this->createusers($arr);
      }
    }
  }

  public function createusers(&$arr) {
    // Create user object.
    $user = User::create();

    //Mandatory settings
    $user->setPassword($arr[6]);
    $user->enforceIsNew();
    $user->setEmail($arr[5]);
    $user->setUsername($arr[2]); //This username must be unique and accept only a-Z,0-9, - _ @ .
    $user->addRole($arr[4]); //E.g: authenticated
    //custom fields
    $user->set("field_first_name", $arr[0]);
    $user->set("field_last_name", $arr[1]);
    $user->set("field_gender", $arr[3]);

    $user->activate();
    $user->save();
  }
}
