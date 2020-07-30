<?php

namespace Drupal\bigcard\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
// use Drupal\file\Entity\File;
use Drupal\Core\Url;
// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Drupal\paragraphs\Entity\Paragraph;

/**
 * Controller routines for page example routes.
 */
class CheckCardForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'check_card_form';
  }

    /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $session = \Drupal::request()->getSession();
    // $name = $session->get('name');
    // $session->set('name','');
    dpm('check card by jieb');

    // $name = $form_state->get('name');

    $form['header'] = array(
      '#type'     => 'item',
      '#markup'   => '',
      '#prefix'   => '<div class="row">',
      '#suffix'   => ''
    );

    // ----------------------------- login ----------------------------------
    $form['id_card_number_header'] = array(
      '#type'     => 'item',
      '#markup'   => '',
      '#prefix'   => '<div class="col-lg-6 col-md-6 col-sm-12 col-12" style="">',
      '#suffix'   => ''
    );
      
    $form['id_card_number'] = array(
        '#type' => 'textfield',
        '#title' => t('ID Card Number'),
        '#attributes' => array('placeholder' => t('ID Card Number')),
        // '#description' => t('***Enter your Credit sales Approval username.'),
        // '#default_value' => $name,
        '#size' => 25,
        
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#name' => 'Submit',
        '#value' => t('Submit'),
        '#prefix'   => '',
        '#suffix'   => ''
    );

    $form['id_card_number_footer'] = array(
      '#type'     => 'item',
      '#markup'   => '',
      '#prefix'   => '',
      '#suffix'   => '</div>'
    );

    // ----------------------------------- end login ---------------------------------

    // ----------------------------------- register ----------------------------------


    $form['privilege_header'] = array(
      '#type'     => 'item',
      '#markup'   => '',
      '#prefix'   => '<div class="col-lg-6 col-md-6 col-sm-12 col-12" style="">',
      '#suffix'   => ''
    );

    $form['privilege'] = array(
      '#type'     => 'item',
      '#markup'   => '<p>privilege...</p>',
    );

    $form['privilege_footer'] = array(
      '#type'     => 'item',
      '#markup'   => '',
      '#prefix'   => '',
      '#suffix'   => '</div>'
    );

    // -------------------------- end register ---------------------------------

    $form['footer'] = array(
      '#type'     => 'item',
      '#markup'   => '',
      '#prefix'   => '',
      '#suffix'   => '</div>'
    );

    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      parent::validateForm($form, $form_state);

      $name = $form_state->getValue('name');
      if (empty(trim($name))){
          $form_state->setErrorByName('name', $this->t('Enter your Credit sales Approval username.'));
      }

      $pass = $form_state->getValue('pass');
      if (empty(trim($pass))){
          $form_state->setErrorByName('pass', $this->t('Enter the password that accompanies your username.'));
      }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $session = \Drupal::request()->getSession();
    $field= $form_state->getValues();
    // $session->set('name', $field['name']);

    $form_state->set('name',  $field['name']);

    /*
    $result =  MainPageController::login_ldap($field['name'], $field['pass']);    
    if($result['status']){
      $uid = \Drupal::service('user.auth')->authenticate($field['name'], $field['name']);

      \Drupal::logger('credit_sales_approval')->notice('login_form > uid : %uid, name : %name.', array('%uid' => $uid, '%name' => $field['name']));
      if($uid){
        $user = \Drupal\user\Entity\User::load($uid);
        user_login_finalize($user);

        $user_destination = \Drupal::destination()->get();
        $response = new RedirectResponse($user_destination);

        $response->send();
      }else{
        \Drupal::messenger()->addMessage(t('ไม่สามารถเข้าระบบได้เนื่องจาก ID : '. $field['name'] .' นี้ไม่ลงทะเบียนกับ Credit sales Approval.'), 'error');
      }
    }else{
      \Drupal::messenger()->addMessage(t($result['message']), 'error');
    }
    */
  }

  function redirect_check_card(array &$form, FormStateInterface $form_state){
    // $nid                = $form['nid']['#value'];       
    $response = new RedirectResponse("/" . $this->language . "/check_card");
    $response->send();
  }

}
