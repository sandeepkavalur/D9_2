<?php

  namespace Drupal\rsvplist\Form;

  use Drupal\Core\Database\Database;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  class RSVPForm extends FormBase{
    public function getFormId(){
      return 'rsvplist_email_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state){
      $node = \Drupal::routeMatch()->getParameter('node');
      // $nid = $node->nid->value;
      // $nid = $node->id();
      $form['email'] = [
        '#title' => t('Email Address'),
        '#type' => 'textfield',
        '#size' => 25,
        '#description' => t("We'll send updates to the email address you've provided."),
        '#required' => TRUE,
      ];

      $form['submit'] = [
        '#type' => 'submit',
        '#value' => t('submit'),
      ];

      // $form['nid'] = [
      //   '#type' => 'hidden',
      //   '#value' => $nid,
      // ];
      return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

    public function submitForm(array &$form, FormStateInterface $form_state){
      \Drupal::messenger()->addMessage(t('The form is working.'));
    }
  }