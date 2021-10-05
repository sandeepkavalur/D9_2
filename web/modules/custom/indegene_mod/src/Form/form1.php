<?php

namespace Drupal\indegene_mod\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Exception ;


class form1 extends FormBase{
  public function getFormId(){
    return 'ex81_hello_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    // $config = \Drupal::config('indegene_mod.settings');
    // kint($config);

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Please enter the title and accept the terms of use of the site.'),
    ];

    $form['candidate_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Name:'),
      '#required' => TRUE,
    );
    $form['candidate_lastname'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate LastName:'),
      '#required' => TRUE,
    );
    $form['candidate_fullname'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate FullName:'),
      '#required' => TRUE,
    );
    $form['candidate_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => FALSE,
    );
    $form['candidate_number'] = array (
      '#type' => 'tel',
      '#title' => t('Mobile No:'),
      '#required' => TRUE,
    );
    $form['accept'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('I accept the terms of use of the site'),
      '#description' => $this->t('Please read and accept the terms of use'),
    );

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    $form['#theme'] = 'custom-form';

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    if (strlen($form_state->getValue('candidate_number')) < 10) {
      $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
    }

    // if (!valid_email_address($form_state->getValue('candidate_mail'))) {
    //   form_set_error('submitted][email_address', t('The email address appears to be invalid.'));
    // }

    echo \Drupal::service('email.validator')
    ->isValid($form_state->getValue('candidate_mail'));

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    // kint($form_state->getValues()); die;

    $connection = \Drupal::service('database');
    try{
      $result = $connection->merge('indegene_mod')
      ->key('candidate_mail', $form_state->getValue('candidate_mail'))
      ->fields([
        'candidate_name' => $form_state->getValue('candidate_name'),
        'candidate_mail' => $form_state->getValue('candidate_mail'),
        'candidate_number' => $form_state->getValue('candidate_number'),
        'candidate_fullname' => $form_state->getValue('candidate_fullname'),
        'candidate_lastname' => $form_state->getValue('candidate_lastname'),
      ])
      ->execute();


      foreach ($form_state->getValues() as $key => $value) {
        \Drupal::messenger()->addMessage($key . ': ' . $value);
      }
    }
    catch (Exception $e){
      $connection->rollBack();
      \Drupal::logger('type')->error($e->getMessage());
    }
  }
}
