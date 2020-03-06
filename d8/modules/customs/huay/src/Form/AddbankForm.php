<?php

namespace Drupal\huay\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;
// use Drupal\credit_sales_approval\Controller\MainPageController;

/**
 * Controller routines for page example routes.
 */
class AddbankForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_bank_form';
  }

    /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // $session = \Drupal::request()->getSession();
    // $name = $session->get('name');
    // $session->set('name','');

    $name = $form_state->get('name');  
    $form['bank'] = [
        '#type' => 'select',
        '#title' => 'เลือกธนาคาร',
        // '#default_value' => (isset($type_customer_tid) ? $type_customer_tid : 1),
        '#options' => array('0'=>'#A',
                            '1'=>'#B',
                            '2'=>'#C',
                            '3'=>'#D'),
        '#attributes' => [
            'id' => 'select-type-customer',
            'name' => 'select_type_customer',
        ],
        '#validated' => TRUE,
    ];

    $form['number_bank'] = array(
        '#type' => 'textfield',
        '#title' => t('เลขที่บัญชี'),
        '#attributes' => array('placeholder' => t('เลขที่บัญชี')),
        // '#description' => t('***Enter your Credit sales Approval username.'),
        '#default_value' => $name,
        '#size' => 25,
    );

    $form['number_bank_confirm'] = array(
        '#type' => 'textfield',
        '#title' => t('ยืนยันเลขที่บัญชี'),
        '#attributes' => array('placeholder' => t('ยืนยันเลขที่บัญชี')),
        // '#description' => t('***Enter your Credit sales Approval username.'),
        '#default_value' => $name,
        '#size' => 25,
    );

    $form['name_bank'] = array(
        '#type' => 'textfield',
        '#title' => t('ชื่อบัญชี'),
        '#attributes' => array('placeholder' => t('ชื่อบัญชี')),
        // '#description' => t('***Enter your Credit sales Approval username.'),
        '#default_value' => $name,
        '#size' => 25,
    );

    $form['add_bank'] = array(
        '#type' => 'submit',
        '#name' => 'add_bank',
        '#value' => t('Add bank'),
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

    // $result =  MainPageController::login_ldap($field['name'], $field['pass']);    
    // if($result['status']){
    //   $uid = \Drupal::service('user.auth')->authenticate($field['name'], $field['name']);

    //   \Drupal::logger('credit_sales_approval')->notice('login_form > uid : %uid, name : %name.', array('%uid' => $uid, '%name' => $field['name']));
    //   if($uid){
    //     $user = \Drupal\user\Entity\User::load($uid);
    //     user_login_finalize($user);

    //     $user_destination = \Drupal::destination()->get();
    //     $response = new RedirectResponse($user_destination);

    //     $response->send();
    //   }else{
    //     \Drupal::messenger()->addMessage(t('ไม่สามารถเข้าระบบได้เนื่องจาก ID : '. $field['name'] .' นี้ไม่ลงทะเบียนกับ Credit sales Approval.'), 'error');
    //   }
    // }else{
    //   \Drupal::messenger()->addMessage(t($result['message']), 'error');
    // }
  }
}