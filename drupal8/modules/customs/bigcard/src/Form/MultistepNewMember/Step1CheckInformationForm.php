<?php

/**
 * @file
 * Contains \Drupal\bigcard\Form\MultistepNewMember\Step1CheckInformationForm.
 */

namespace Drupal\bigcard\Form\MultistepNewMember;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Url;
use Drupal\bigcard\Utils\Utils;


class Step1CheckInformationForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'step_1_check_information_form';
  }

  public function get_wizard(){
    return '<div class="register_steps">
              <div class="main-wrapper">
                  <div id="signup_steps">
                      <ul>
                          <li style="float: left; width: 25%;" id="step_check" class="writing">
                              <i class="fas fa-pencil-alt pencil-icon opaque"></i>
                              <a href="#" title="' . $this->t('ตรวจสอบข้อมูล') . '"><span>1. </span>' . $this->t('ตรวจสอบข้อมูล') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_signup" class="pending">
                              <i class="far fa-check-circle transparent"></i>
                              <a href="#" title="' . $this->t('ข้อมูลสำหรับเข้าสู่ระบบ') . '"><span>2. </span>' . $this->t('ข้อมูลสำหรับเข้าสู่ระบบ') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_password" class="pending">
                              <i class="far fa-check-circle transparent"></i>
                              <a href="#" title="' . $this->t('รหัสผ่าน') . '"><span>3. </span>' . $this->t('รหัสผ่าน') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_complete" class="pending">
                              <i class="far fa-check-circle transparent"></i>
                              <a href="#" title="' . $this->t('ลงทะเบียนเสร็จสมบูรณ์') . '"><span>4. </span>' . $this->t('ลงทะเบียนเสร็จสมบูรณ์') . '</a>
                          </li>
                      </ul>
                  </div>
              </div>
          </div>';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $type = \Drupal::service('current_route_match')->getParameter('type'); 
    if(empty($this->store->get('type'))){
      parent::deleteStore([]);
    }
    $this->store->set('type', $type);

    switch($this->store->get('type')){
      case 'normal':{
        return $this->normalForm($form, $form_state);
      }

      // case 'gpf':{
      //   return $this->gpfForm($form, $form_state);
      // break;
      // }

      case 'gpf' :
      case 'junior':{
        return $this->juniorForm($form, $form_state);
      break;
      }
    }

    return $form;
  }

  private function normalForm(array $form, FormStateInterface $form_state){
    $data       = $form_state->get('data');

    $form['#tree'] = TRUE;
    $form['status'] = array(
      '#type' => 'hidden',
      '#value' => 1,
    );

    /*
    $form['container1'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container1">' . $this->get_wizard(),
        '#suffix' => '</div>',
      );
    */
    if(empty($data)){

      $form['container'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container" class="row">
                          <div class="col-12 px-0">
                            ' . $this->get_wizard() . '
                          </div>
                       ',
        '#suffix' => '</div>',
      );
      
      $form['container']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container-left" class="col-lg-6 col-md-6 col-sm-12 col-12">
                          <h3 class="title_head">กรุณากรอก เลขบัตรประชาชน หรือเลขหนังสือเดินทาง</h3>',
        '#suffix' => '</div>',
      );

      // $form['container']['left']['log'] = array(
      //   '#type' => 'markup',
      //   '#markup' => $this->store->get('data_check_exists_id_card')['is_exists'] . ' ' . $this->store->get('data_check_exists_id_card')['is_online_register']
      // );

      // $form['container']['left']['type'] = array(
      //   '#type' => 'select',
      //   // '#title' => t('เลือก'),
      //   '#default_value' => 1,
      //   '#options' => array(1=>'เลขบัตรประชาชน', 3=>'เลขหนังสือเดินทาง'),
      //   '#attributes' => [
      //     'id' => 'select-type',
      //     'name' => 'select_type',
      //   ],
      // );

      $form['container']['left']['id_card'] = array(
        '#type' => 'textfield',
        // '#title' => $this->t('เลขบัตรประชาชน'),
        '#default_value' => $this->store->get('id_card') ? $this->store->get('id_card') : '',
        // '#prefix' => "<div>กรุณากรอก เลขบัตรประชาชน หรือเลขหนังสือเดินทาง",
        // '#suffix' => "</div>",
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          // 'visible' => [
          //   ':input[name="select_type"]' => ['value' => 1],
          // ],
        ],
        '#attributes'=>[
          'placeholder' => t('เลขบัตรประชาชน หรือเลขหนังสือเดินทาง'),
          'id' => 'container-id-card',
          'name' => 'container_id_card',
        ],
        '#description'=>$this->t(''),
        '#prefix' => "<div class='productForm'>",
        '#suffix' => "</div>",
      );

      

      /*
      $form['container']['left']['pass_sport'] = array(
        '#type' => 'textfield',
        // '#title' => $this->t('เลขหนังสือเดินทาง'),
        // '#default_value' => $this->store->get('name') ? $this->store->get('name') : '',
        // '#prefix' => "<div>กรุณากรอก เลขบัตรประชาชน หรือเลขหนังสือเดินทาง",
        // '#suffix' => "</div>",
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          // 'visible' => [
          //   ':input[name="select_type"]' => ['value' => 3],
          // ],
        ],
        '#description'=>$this->t(''),
        '#attributes'=>[
          'placeholder' => t('เลขหนังสือเดินทาง'),
          'id' => 'container-pass-sport',
          'name' => 'container_pass_sport',
        ]
      );
      */
  
      $form['container']['left']['s1confirm'] = array(
        '#type' => 'submit',
        '#name' => 's1confirm',
        '#value' => $this->t('ยืนยัน'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        '#suffix' => '</div></div>',
      );

      $form['container']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div>',
      );
    }else if($data['sstep'] == 1){
      //ใส่เบอร์โทรศัพท์
      $form['container1'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container1">' . $this->get_wizard(),
        '#suffix' => '</div>',
      );

  
      $form['container1']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container1-left" class="col-lg-6 col-md-6 col-sm-12 col-12">
                          <h3 class="title_head">เบอร์โทรศัพท์มือถือ</h3>',
        '#suffix' => '</div>',
      );

      $form['container1']['left']['log'] = array(
        '#type' => 'markup',
        '#markup' => $this->store->get('data_check_exists_id_card')['is_exists'] . $this->store->get('data_check_exists_id_card')['is_online_register']
        
      );

      // $form['container']['left']['dial_code'] = array(
      //   '#type' => 'hidden',
      //   '#value' => '66',
      // );

      $form['container']['left']['sub_step'] = array(
        '#type' => 'hidden',
        '#value' => 's2Confirm',
      );
  
      $form['container1']['left']['tel'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
        // '#default_value' => $this->store->get('tel'),
        // '#description'=>$this->t(''),
        '#prefix' => '',
        '#suffix' => '<div id="tel-description" class="fs-xxs">กดยืนยันเพื่อรับรหัสผ่าน 6 หลัก</div>',
        // '#description'=>$this->t(''),
        '#attributes' => [
          'id' => 'container1-tel',
          'name' => 'container1_tel',
        ],
      );

      $form['container1']['left']['s2confirm'] = array(
        '#type' => 'submit',
        '#name' => 's2confirm',
        '#value' => $this->t('ยืนยัน'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container1',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container1']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        '#suffix' => '</div>',
      );

      $form['container1']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div>',
      );

    }else if($data['sstep'] == 2){
      //กรอก otp
      $form['container2'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container2">' . $this->get_wizard(),
        '#suffix' => '</div>',
      );
  
      $form['container2']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container2-left" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        '#suffix' => '</div>',
      );
      
      
      $otp_ref = $this->store->get('otp_ref');
      $form['container2']['left']['otp'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('กรุณากรอกรหัส OTP * ( Ref = ' . $otp_ref . ' )'),
        // '#title' => $this->t('กรุณากรอกรหัส OTP * ( Ref = ZXPQ )'),
        // '#default_value' => $this->store->get('name') ? $this->store->get('name') : '',
        '#attributes' => [
          'id' => 'container2-otp',
          'name' => 'container2_otp',
        ],
        '#prefix' => "",
        '#suffix' => "<div id='otp-description'></div>",
      );

      $form['container2']['left']['s3confirm'] = array(
        '#type' => 'submit',
        '#name' => 's3confirm',
        '#value' => $this->t('ยืนยันรหัส OTP'),
        // '#description'=>$this->t('ขอรหัส OTP อีกครั้ง หากไม่ได้รับรหัสผ่านทาง SMS ภายใน 5 นาที'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container2',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container2']['left']['s3get_otp'] = array(
        '#type' => 'submit',
        '#name' => 's3get_otp',
        '#value' => $this->t('ขอรหัส OTP อีกครั้ง'),
        // '#description'=>$this->t('หากไม่ได้รับรหัสผ่านทาง SMS ภายใน 5 นาที'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container2',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
        '#prefix' => '',
        '#suffix' => '<div class="s3get_otp_description fs-xxs fw-700">หากไม่ได้รับรหัสผ่านทาง SMS ภายใน 5 นาที</div>',
      );

      $form['container2']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container2-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        // '#suffix' => '</div>',
      );

      $form['container2']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div></div>',
      );
    }else if($data['sstep'] == 3){
      //มีบัญชีอยู่แล้ว
      $form['container3'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container3">' . $this->get_wizard(),
        '#suffix' => '</div>',
      );
      

      $form['container3']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container3-left" class="col-lg-6 col-md-6 col-sm-12 col-12">
                          <h3 class="title_head">เบอร์โทรศัพท์มือถือ</h3>
                          <div>คุณเป็นสมาชิกบิ๊กการ์ดอยู่แล้ว บัญชีของคุณผูกกับ</div>',
        '#suffix' => '</div>',
      );

      $form['container3']['left']['log'] = array(
        '#type' => 'markup',
        '#markup' => $this->store->get('data_check_exists_id_card')['is_exists']. $this->store->get('data_check_exists_id_card')['is_online_register']
      );
      

      $tel = $this->store->get('data_find_member')['mobile_phone'];
      $full_tel = Utils::getDisplayMobilePhone($tel);
      $tel = (empty($full_tel)) ? '' : substr($full_tel, 0, 3) . '-XXX' . '-X' . substr($full_tel, 7, 3);
      $form['container3']['left']['tel'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
        '#default_value' => $tel,
        // '#description'=>$this->t(''),
        '#prefix' => '',
        '#suffix' => '<div id="tel-description" class="fs-xxs fw-600">กดยืนยันเพื่อรับรหัสผ่าน 6 หลัก</div>',
        '#attributes' => [
          'id' => 'container3-tel',
          'name' => 'container3_tel',
          'readonly' => 'readonly'
        ],
      );

      $form['container3']['left']['s4confirm'] = array(
        '#type' => 'submit',
        '#name' => 's4confirm',
        '#value' => $this->t('ยืนยัน'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container3',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container3']['left']['annotation'] = array(
        '#type' => 'markup',
        '#markup' => '<span class="fs-xxs fw-600">(หากข้อมูลไม่ถูกต้องกรุณาติดต่อ Call Center 1756)</span>'
      );

      $form['container3']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container3-right">',
        '#suffix' => '</div>',
      );

      $form['container3']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div>',
      );
    }
    return $form;
  }

  private function gpfForm(array $form, FormStateInterface $form_state){

    
    return $form;
  }

  private function juniorForm(array $form, FormStateInterface $form_state){
 
    $data       = $form_state->get('data');

    $form['#tree'] = TRUE;
    $form['status'] = array(
      '#type' => 'hidden',
      '#value' => 1,
    );

    if(empty($data)){

      $form['container'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container" class="row">
                          <div class="col-12 px-0">
                            ' . $this->get_wizard() . '
                          </div>
                       ',
        '#suffix' => '</div>',
      );
      
      $form['container']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container-left" class="col-lg-6 col-md-6 col-sm-12 col-12">
                          <h3 class="title_head">กรุณากรอก เลขบัตรประชาชน</h3>',
        '#suffix' => '</div>',
      );

      $form['container']['left']['log'] = array(
        '#type' => 'markup',
        '#markup' => $this->store->get('data_check_exists_id_card')['is_exists'] . ' ' . $this->store->get('data_check_exists_id_card')['is_online_register']
        // '#markup' => $this->store->get('data_check_exists_id_card')
      );

      $form['container']['left']['id_card'] = array(
        '#type' => 'textfield',
        // '#title' => $this->t('เลขบัตรประชาชน'),
        '#default_value' => $this->store->get('id_card') ? $this->store->get('id_card') : '',
        // '#prefix' => "<div>กรุณากรอก เลขบัตรประชาชน หรือเลขหนังสือเดินทาง",
        // '#suffix' => "</div>",
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          // 'visible' => [
          //   ':input[name="select_type"]' => ['value' => 1],
          // ],
        ],
        '#attributes'=>[
          'placeholder' => t('เลขบัตรประชาชน'),
          'id' => 'container-id-card',
          'name' => 'container_id_card',
        ],
        '#description'=>$this->t(''),
        '#prefix' => "<div class='productForm'>",
        '#suffix' => "</div>",
      );
  
      $form['container']['left']['s1confirm'] = array(
        '#type' => 'submit',
        '#name' => 's1confirm',
        '#value' => $this->t('ยืนยัน'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        '#suffix' => '</div></div>',
      );

      $form['container']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div>',
      );

    }else if($data['sstep'] == 1){
      $form['container1'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container1">' . $this->get_wizard(),
        '#suffix' => '</div>',
      );

      $form['container1']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container1-left" class="col-lg-6 col-md-6 col-sm-12 col-12">
                          <h3 class="title_head">เบอร์โทรศัพท์มือถือ</h3>',
        '#suffix' => '</div>',
      );

      $form['container1']['left']['log'] = array(
        '#type' => 'markup',
        '#markup' => $this->store->get('data_check_exists_id_card')['is_exists'] . $this->store->get('data_check_exists_id_card')['is_online_register']
        
      );
  
      if($this->store->get('data_check_exists_id_card')['is_exists'] == 'N' && $this->store->get('data_check_exists_id_card')['is_online_register'] == 'N')
      {
        $form['container1']['left']['tel'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
          '#default_value' => $this->store->get('tel'),
          // '#description'=>$this->t(''),
          '#prefix' => '',
          '#suffix' => '<div id="tel-description" class="fs-xxs">กดยืนยันเพื่อรับรหัสผ่าน 6 หลัก</div>',
          // '#description'=>$this->t(''),
          '#attributes' => [
            'id' => 'container1-tel',
            'name' => 'container1_tel',
          ],
        );
      }else
      {
        $form['container1']['left']['tel'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
          '#default_value' => $this->store->get('tel'),
          '#attributes'=>array('readonly' => 'readonly'),
          // '#description'=>$this->t(''),
          '#prefix' => '',
          '#suffix' => '<div id="tel-description" class="fs-xxs">กดยืนยันเพื่อรับรหัสผ่าน 6 หลัก</div>',
          // '#description'=>$this->t(''),
          '#attributes' => [
            'id' => 'container1-tel',
            'name' => 'container1_tel',
          ],
        );
      }

      $form['container1']['left']['tel'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
        '#default_value' => $this->store->get('tel'),
        // '#description'=>$this->t(''),
        '#prefix' => '',
        '#suffix' => '<div id="tel-description" class="fs-xxs">กดยืนยันเพื่อรับรหัสผ่าน 6 หลัก</div>',
        // '#description'=>$this->t(''),
        '#attributes' => [
          'id' => 'container1-tel',
          'name' => 'container1_tel',
        ],
      );

      $form['container1']['left']['s2confirm'] = array(
        '#type' => 'submit',
        '#name' => 's2confirm',
        '#value' => $this->t('ยืนยัน'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container1',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container1']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        '#suffix' => '</div>',
      );

      $form['container1']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div>',
      );

    }else if($data['sstep'] == 2){
      $form['container2'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container2">' . $this->get_wizard(),
        '#suffix' => '</div>',
      );
  
      $form['container2']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container2-left" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        '#suffix' => '</div>',
      );

      $otp_ref = $this->store->get('otp_ref');
      $form['container2']['left']['otp'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('กรุณากรอกรหัส OTP * ( Ref = ' . $otp_ref . ' )'),
        // '#title' => $this->t('กรุณากรอกรหัส OTP * ( Ref = ZXPQ )'),
        // '#default_value' => $this->store->get('name') ? $this->store->get('name') : '',
        '#attributes' => [
          'id' => 'container2-otp',
          'name' => 'container2_otp',
        ],
        '#prefix' => "",
        '#suffix' => "<div id='otp-description'></div>",
      );

      $form['container2']['left']['s3confirm'] = array(
        '#type' => 'submit',
        '#name' => 's3confirm',
        '#value' => $this->t('ยืนยันรหัส OTP'),
        // '#description'=>$this->t('ขอรหัส OTP อีกครั้ง หากไม่ได้รับรหัสผ่านทาง SMS ภายใน 5 นาที'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container2',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container2']['left']['s3get_otp'] = array(
        '#type' => 'submit',
        '#name' => 's3get_otp',
        '#value' => $this->t('ขอรหัส OTP อีกครั้ง'),
        // '#description'=>$this->t('หากไม่ได้รับรหัสผ่านทาง SMS ภายใน 5 นาที'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container2',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
        '#prefix' => '',
        '#suffix' => '<div class="s3get_otp_description fs-xxs fw-700">หากไม่ได้รับรหัสผ่านทาง SMS ภายใน 5 นาที</div>',
      );

      $form['container2']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container2-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
        // '#suffix' => '</div>',
      );

      $form['container2']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div></div>',
      );
    }else if($data['sstep'] == 3){
      $form['container3'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container3">' . $this->get_wizard(),
        '#suffix' => '</div>',
      );
      

      $form['container3']['left'] = array(
        '#type' => 'container',
        '#prefix' => '<div class="row container-px">
                        <div id="container3-left" class="col-lg-6 col-md-6 col-sm-12 col-12">
                          <h3 class="title_head">เบอร์โทรศัพท์มือถือ</h3>
                          <div>คุณเป็นสมาชิกบิ๊กการ์ดอยู่แล้ว บัญชีของคุณผูกกับ</div>',
        '#suffix' => '</div>',
      );

      $form['container3']['left']['log'] = array(
        '#type' => 'markup',
        '#markup' => $this->store->get('data_check_exists_id_card')['is_exists']. $this->store->get('data_check_exists_id_card')['is_online_register']
      );
      

      $tel = $this->store->get('data_find_member')['mobile_phone'];
      $full_tel = Utils::getDisplayMobilePhone($tel);
      $tel = (empty($full_tel)) ? '' : substr($full_tel, 0, 3) . '-XXX' . '-X' . substr($full_tel, 7, 3);
      $form['container3']['left']['tel'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
        '#default_value' => $tel,
        // '#description'=>$this->t(''),
        '#prefix' => '',
        '#suffix' => '<div id="tel-description" class="fs-xxs fw-600">กดยืนยันเพื่อรับรหัสผ่าน 6 หลัก</div>',
        '#attributes' => [
          'id' => 'container3-tel',
          'name' => 'container3_tel',
          'readonly' => 'readonly',
        ],
      );

      $form['container3']['left']['s4confirm'] = array(
        '#type' => 'submit',
        '#name' => 's4confirm',
        '#value' => $this->t('ยืนยัน'),
        '#submit' => ['::submitAjax'],
        '#ajax' => [
          'callback' => '::callbackAjax',
          'wrapper' => 'container3',
          'progress' => array(
              // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
              'type' => 'throbber',
              // Message to show along progress graphic. Default: 'Please wait...'.
              'message' => NULL,
          ),
        ],
      );

      $form['container3']['left']['annotation'] = array(
        '#type' => 'markup',
        '#markup' => '<span class="fs-xxs fw-600">(หากข้อมูลไม่ถูกต้องกรุณาติดต่อ Call Center 1756)</span>'
      );

      $form['container3']['right'] = array(
        '#type' => 'container',
        '#prefix' => '<div id="container3-right">',
        '#suffix' => '</div>',
      );

      $form['container3']['right']['text'] = array(
        '#type' => 'item',
        '#prefix' => Utils::getTextRight(),
        '#suffix' => '</div>',
      );
    }

    return $form;
  }

  public function submitAjax(array &$form, FormStateInterface $form_state) {

    switch($this->store->get('type')){
      case 'normal':{
        // drupal_set_message("s1confirm_form_submit.");
        $btn_name   = $form_state->getTriggeringElement()['#name'];
        $data       = $form_state->get('data');


        // select_type, id_card, pass_sport, tel
        switch($btn_name){
          case 's1confirm':{
            // $select_type =  $form_state->getUserInput()['select_type'];
            $user_input    = $form_state->getUserInput()['container_id_card'];

            // 13 digit = (1) id card
            // others = (3) passport
            $select_type = (strlen($user_input) == '13') ? '1' : '3';
            $this->store->set('select_type', $select_type);
            $this->store->set('is_foreigner', false);

            switch($select_type){
              case '1':{
                $id_card = $user_input;

                // $data_check_exists_id_card = Utils::check_exists_id_card_api($id_card);
                  // $this->store->set('data_check_exists_id_card', $id_card);
                  // $form_state->setRebuild();

                if(!empty($id_card) && strlen($id_card) == '13'){

                  $this->store->set('id_card', $id_card);

                  // call api
                  // $data_check_exists_id_card['is_exists'] = $id_card;
                  $data_check_exists_id_card = Utils::check_exists_id_card_api($id_card);
                  $this->store->set('data_check_exists_id_card', $data_check_exists_id_card);

                  if($data_check_exists_id_card['is_exists'] == 'Y'){
                    // มีบัญชี bigcard แล้ว

                    if($data_check_exists_id_card['is_online_register'] == 'Y'){
                      // YY
                      dpm('YY');
                      // ลงทะเบียนออนไลน์แล้ว
                      // แสดง link ไปหน้า login
                    }else{
                      // YN
                      dpm('YN');

                      // ยังไม่ลงทะเบียนออนไลน์
                      // แสดงเบอร์ที่ผูกไว้
                      $data_find_member = Utils::find_member_api($id_card);
                      $this->store->set('data_find_member', $data_find_member);
                      $form_state->set('data', array('sstep'=>3));
                    }
                  }else{
                    dpm('NN');
                    // NN
                    // ยังไม่มีบัญชี bigcard
                    // ไปหน้ากรอกเบอร์โทร
                    $form_state->set('data', array('sstep'=>1));
                  }
                  $form_state->setRebuild();
                }
                
              break;
              }

              case '3':{
                $pass_sport = $user_input;

                if(!empty($pass_sport) && (strlen($pass_sport) > 5)){

                  $this->store->set('passport', $pass_sport);

                  // call api
                  $data_check_exists_id_card = Utils::check_exists_id_card_api($pass_sport);
                  $this->store->set('data_check_exists_id_card', $data_check_exists_id_card);

                  if($data_check_exists_id_card['is_exists'] == 'Y'){
                    // มีบัญชี bigcard แล้ว
                    
                    if($data_check_exists_id_card['is_online_register'] == 'Y'){
                      // YY
                      // ลงทะเบียนออนไลน์แล้ว
                      // แสดง link ไปหน้า login
                    }else{
                      // YN
                      // ยังไม่ลงทะเบียนออนไลน์
                      // แสดงเบอร์ที่ผูกไว้
                      $data_find_member = Utils::find_member_api($pass_sport);
                      $this->store->set('data_find_member', $data_find_member);

                      $nationality = $data_find_member['nationality'];
                      $id_card_type = $data_find_member['id_card_type'];

                      if($nationality == '999' && $id_card_type == '3'){ // ต่างชาติ + Passport
                        $this->store->set('is_foreigner', true);
                      }
                      $form_state->set('data', array('sstep'=>3));
                    }
                  }else{
                    // NN
                    // ยังไม่มีบัญชี bigcard
                    // ไม่ให้สมัคร ให้สมัครที่ counter เท่านั้น
                    // $form_state->set('data', array('sstep'=>1));
                  }
                  $form_state->setRebuild();
                  // $form_state->set('data', array('sstep'=>1));
                  // $form_state->setRebuild();
                }
              break;
              }
            }

            break;
          }
          case 's2confirm':{
            $tel    = $form_state->getUserInput()['container1_tel'];
            if(!empty($tel)){
              $this->store->set('tel', $tel);
              // call send OTP api
              $otp_number = rand(100000, 999999);
              $this->store->set('otp_number', $otp_number);

              // otp will expire in 5 minutes
              $otp_expired_time = time() + (5*60);
              $this->store->set('otp_expired_time', $otp_expired_time);

              // otp ref code
              $otp_ref = chr(rand(65,90)) .  chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
              $this->store->set('otp_ref', $otp_ref);

              $data_send_otp = Utils::send_otp_api($tel, $otp_number, $otp_ref);
              $this->store->set('data_send_otp', $data_send_otp);
              if($data_send_otp['code'] == '000'){ // PENDING
                // send OTP success
                $form_state->set('data', array('sstep'=>2));
              }else{
                // cannot send OTP
              }
              // $form_state->set('data', array('sstep'=>2));
              $form_state->setRebuild();
            }
          break;
          }
          case 's3confirm':{
            $otp    = $form_state->getUserInput()['container2_otp'];
            if(!empty($otp)){
              $otp_number = $this->store->get('otp_number');
              $otp_expired_time = $this->store->get('otp_expired_time');
              if($otp_number == $otp && time() <= $otp_expired_time){
                // ใส่ OTP ถูกต้อง + otp ยังไม่หมดอายุ
                // ไปหน้า register
              }else{
                // ใส่ OTP ผิด
                // แสดงข้อความแจ้งเตือน
              }
              // $form_state->set('data', array('sstep'=>3));
              $form_state->setRebuild();
            }
          break;
          }
          case 's3get_otp':{
            // $form_state->set('data', array('sstep'=>3));
            // $form_state->setRebuild();

            $tel = $this->store->get('tel');
            // call send OTP api
            $otp_number = rand(100000, 999999);
            $this->store->set('otp_number', $otp_number);

            // otp will expire in 5 minutes
            $otp_expired_time = time() + (5*60);
            $this->store->set('otp_expired_time', $otp_expired_time);

            // otp ref code
            $otp_ref = $this->store->get('otp_ref');

            $data_send_otp = Utils::send_otp_api($tel, $otp_number, $otp_ref);
            $this->store->set('data_send_otp', $data_send_otp);
            if($data_send_otp['code'] == '000'){ // PENDING
              // send OTP success
            }else{
              // cannot send OTP
            }
            // $form_state->set('data', array('sstep'=>2));
            $form_state->setRebuild();
            break;
          }
          case 's4confirm':{
            
            $tel = $this->store->get('data_find_member')['mobile_phone'];
            $this->store->set('tel', $tel);

            // call send OTP api
            $otp_number = rand(100000, 999999);
            $this->store->set('otp_number', $otp_number);

            // otp will expire in 5 minutes
            $otp_expired_time = time() + (5*60);
            $this->store->set('otp_expired_time', $otp_expired_time);

            // otp ref code
            $otp_ref = chr(rand(65,90)) .  chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
            $this->store->set('otp_ref', $otp_ref);

            $data_send_otp = Utils::send_otp_api($tel, $otp_number, $otp_ref);
            $this->store->set('data_send_otp', $data_send_otp);
   
            if($data_send_otp['code'] == '000'){ // PENDING
              // send OTP success
              $form_state->set('data', array('sstep'=>2));
            }else{
              // cannot send OTP
            }
            $form_state->setRebuild();
          break;
          }
        }

      break;
      }

      // case 'gpf':{

      // break;
      // }
      case 'gpf':
      case 'junior':{

        $btn_name   = $form_state->getTriggeringElement()['#name'];
        $data       = $form_state->get('data');

         // select_type, id_card, pass_sport, tel
         switch($btn_name){
          case 's1confirm':{
            // $select_type =  $form_state->getUserInput()['select_type'];
            $user_input    = $form_state->getUserInput()['container_id_card'];

            // 13 digit = (1) id card
            // others = (3) passport
            $select_type = (strlen($user_input) == '13') ? '1' : '3';


            switch($select_type){
              case '1':{
                $id_card = $user_input;

                // $data_check_exists_id_card = Utils::check_exists_id_card_api($id_card);
                  // $this->store->set('data_check_exists_id_card', $id_card);
                  // $form_state->setRebuild();

                if(!empty($id_card) && strlen($id_card) == '13'){

                  $this->store->set('select_type', $select_type);
                  $this->store->set('id_card', $id_card);

                  // call api
                  // $data_check_exists_id_card['is_exists'] = $id_card;
                  $data_check_exists_id_card = Utils::check_exists_id_card_api($id_card);
                  $this->store->set('data_check_exists_id_card', $data_check_exists_id_card);

                  if($data_check_exists_id_card['is_exists'] == 'Y'){
                    // มีบัญชี bigcard แล้ว

                    $data_find_member = Utils::find_member_api($id_card);
                    $this->store->set('data_find_member', $data_find_member);

                    //if($data_find_member['cardClass'] == 'ONL' || $data_find_member['cardClass'] == 'ONL-SPO' || $data_find_member['cardClass'] == 'SLV'|| $data_find_member['cardClass'] == 'GOLD')
                    if( $data_find_member['cardClass'] == 'SILV' || $data_find_member['cardClass'] == 'ONL')
                    {
                      $form_state->set('data', array('sstep'=>3));
                    }

                    // if($data_check_exists_id_card['is_online_register'] == 'Y'){
                    //   // YY
                    //   dpm('YY');
                    //   // ลงทะเบียนออนไลน์แล้ว
                    //   // แสดง link ไปหน้า login

               

                    // }else{
                    //   // YN
                    //   dpm('YN');

                    //   // ยังไม่ลงทะเบียนออนไลน์
                    //   // แสดงเบอร์ที่ผูกไว้
                    //   $form_state->set('data', array('sstep'=>3));
                    // }
                  }else{
                    dpm('NN');
                    // NN
                    // ยังไม่มีบัญชี bigcard
                    // ไปหน้ากรอกเบอร์โทร
                    $form_state->set('data', array('sstep'=>1));
                  }
                  // $form_state->setRebuild();
                  // $form_state->set('data', array('sstep'=>1));
                  $form_state->setRebuild();
                }
                
              break;
              }

            }

            break;
          }
          case 's2confirm':{
            // NN กรอกเบอร์เอง
            $tel    = $form_state->getUserInput()['container1_tel'];
            // $dial_code = $form_state->getUserInput()['container']['left']['dial_code'];
            // $full_tel = $dial_code . $tel;

            if(!empty($tel)){
              $this->store->set('tel', $tel);
              // $this->store->set('dial_code', $dial_code);

              // call send OTP api
              $otp_number = rand(100000, 999999);
              $this->store->set('otp_number', $otp_number);

              // otp will expire in 5 minutes
              $otp_expired_time = time() + (5*60);
              $this->store->set('otp_expired_time', $otp_expired_time);

              // otp ref code
              $otp_ref = chr(rand(65,90)) .  chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
              $this->store->set('otp_ref', $otp_ref);

              $data_send_otp = Utils::send_otp_api($tel, $otp_number, $otp_ref);
              $this->store->set('data_send_otp', $data_send_otp);
              
              if($data_send_otp['code'] == '000'){ // PENDING
                // send OTP success
                $form_state->set('data', array('sstep'=>2));
              }else{
                // cannot send OTP
              }
              // $form_state->set('data', array('sstep'=>2));
              $form_state->setRebuild();
            }

          break;
          }
          case 's3confirm':{
            $otp    = $form_state->getUserInput()['container2_otp'];
            if(!empty($otp)){
              $otp_number = $this->store->get('otp_number');
              $otp_expired_time = $this->store->get('otp_expired_time');
              if($otp_number == $otp && time() <= $otp_expired_time){
                // ใส่ OTP ถูกต้อง + otp ยังไม่หมดอายุ
                // ไปหน้า register
              }else{
                // ใส่ OTP ผิด
                // แสดงข้อความแจ้งเตือน
              }
              // $form_state->set('data', array('sstep'=>3));
              $form_state->setRebuild();
            }
          break;
          }
          case 's3get_otp':{
            // ขอเลข OTP อีกครั้ง

            // $form_state->set('data', array('sstep'=>3));
            // $form_state->setRebuild();

            $tel = $this->store->get('tel');
            // $dial_code = $this->store->get('dial_code');
            // $full_tel = $dial_code . $tel;

            // call send OTP api
            $otp_number = rand(100000, 999999);
            $this->store->set('otp_number', $otp_number);
            // $this->store->set('dial_code', $dial_code);
            
            // otp will expire in 5 minutes
            $otp_expired_time = time() + (5*60);
            $this->store->set('otp_expired_time', $otp_expired_time);
            
            // \Drupal::logger('jieb')->notice($otp_number);

            // otp ref code
            $this->store->get('otp_ref');

            $data_send_otp = Utils::send_otp_api($tel, $otp_number, $otp_ref);
            $this->store->set('data_send_otp', $data_send_otp);
            if($data_send_otp['code'] == '000'){ // PENDING
              // send OTP success
            }else{
              // cannot send OTP
            }
            // $form_state->set('data', array('sstep'=>2));
            $form_state->setRebuild();
            break;
          }
          case 's4confirm':{
            // call send OTP api

            $tel = $this->store->get('data_find_member')['mobile_phone'];
            //$tel = $form_state->getUserInput()['container3_tel'];
            $this->store->set('tel', $tel);
            $otp_number = rand(100000, 999999);
            $this->store->set('otp_number', $otp_number);

            // otp will expire in 5 minutes
            $otp_expired_time = time() + (5*60);
            $this->store->set('otp_expired_time', $otp_expired_time);

            // otp ref code
            $otp_ref = chr(rand(65,90)) .  chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
            $this->store->set('otp_ref', $otp_ref);
           
            $data_send_otp = Utils::send_otp_api($tel, $otp_number, $otp_ref);
            $this->store->set('data_send_otp', $data_send_otp);

            if($data_send_otp['code'] == '000'){ // PENDING
              // send OTP success
              $form_state->set('data', array('sstep'=>2));
            }else{
              // cannot send OTP
            }
            $form_state->setRebuild();
          break;
          }
        }


      break;
      }
    }
  }

  public function callbackAjax(array &$form, FormStateInterface $form_state) {
    // dpm('callbackajax');
    $type = $this->store->get('type');
    switch($type){
      case 'normal':{
        $css_input = array(
          'border' => '1px dashed #eb340a',
          'background-color' => '#faebe7',
        );
        $css_description = array(
          'color' => 'red',
          'font-size' => '16px'
        );
        $css_clear = array(
          'border' => '1px solid #ced4da',
          'background-color' => 'transparent'
        );
        $css_clear_disable = array(
          'border' => '1px solid #ced4da',
          'background-color' => 'background-color: #e9ecef'
        );
    
        $btn_name   = $form_state->getTriggeringElement()['#name'];
        $ajax_response = new AjaxResponse();
    
        // reset all field
        $selector_input = '#container-id-card';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
        $selector_description = '#edit-container-left-id-card--description';
        $ajax_response->addCommand(new HtmlCommand($selector_description, ''));
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    
        // $selector_input = '#container-pass-sport';
        // $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
        // $selector_description = '#edit-container-left-pass-sport--description';
        // $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
        // $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    
        $selector_input = '#container1-tel';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
        $ajax_response->addCommand(new HtmlCommand('#tel-description', ''));
    
        $selector_input = '#container2-otp';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
        $ajax_response->addCommand(new HtmlCommand('#otp-description', ''));
    
        // ---------------------------------------
    
        switch($btn_name){
          case 's1confirm':{

            // $select_type=  $form_state->getUserInput()['select_type'];
            // $this->store->set('select_type', $select_type);
    
            $user_input = $form_state->getUserInput()['container_id_card'];
    
            // 13 digit = (1) id card
            // others = (3) passport
            $select_type = (strlen($user_input) == '13') ? '1' : '3';
    
            // ----- test data ------
            // $text = '<span style="color:red;font-size:23px;">' . $user_input;
            // $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
            // return $ajax_response;
    
            switch($select_type){
              case '1':{
                $id_card = $user_input;
                // $id_card = $form_state->getUserInput()['container_id_card'];
                
    
                if(empty($id_card)){
                  $text_description = 'ข้อมูลนี้จำเป็น';
                  // $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
                  // $ajax_response->addCommand(new CssCommand('.productForm', ['display' => 'none']));
                  // $ajax_response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.productForm', 'css', array('background', '#dff0d8')));
                  
                  // $ajax_response->addCommand(new InvokeCommand('#edit-container-id-card', 'focus'));
      
                  # Commands Ajax - CssCommand()
                  $selector_input = '#container-id-card';
                  $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
                  $selector_description = '#edit-container-left-id-card--description';
                  $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                  $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                  return $ajax_response;
                }else if(!is_numeric($id_card) || strlen($id_card) != '13'){
                // }else if(strlen($id_card) != '13'){
                  $selector_input = '#container-id-card';
                  $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
                  $text_description = 'เลขบัตรประชาชน ไม่ถูกต้อง';
                  $selector_description = '#edit-container-left-id-card--description';
                  $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                  $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                  return $ajax_response;
                }else{

                 

                  $data_check_exists_id_card = $this->store->get('data_check_exists_id_card');
                  if($data_check_exists_id_card['is_exists'] == 'Y'){
                    // มีบัญชี bigcard แล้ว
    
                    if($data_check_exists_id_card['is_online_register'] == 'Y'){
                      // YY
                      // ลงทะเบียนออนไลน์แล้ว
                      // แสดง link ไปหน้า login
                      $text = '<span style="color:red;font-size:23px;">คุณมีบัญชี บิ๊กการ์ด ออนไลน์แล้ว</span> <a href="/customer/account/login"> Login</a>';
                      $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                      return $ajax_response;
                    }else{
                      // YN
                      // ยังไม่ลงทะเบียนออนไลน์
                      // ไปหน้าแสดงเบอร์ที่ผูกไว้

                      
                    }
                  }else{
                    // NN
                    // ยังไม่มีบัญชี bigcard
                    // ไปหน้ากรอกเบอร์โทร
                  }
                  return $form;                  
                }
    
              break;
              }
    
              case '3':{
                $pass_sport = $user_input;
                // $pass_sport = $form_state->getUserInput()['container_pass_sport'];
    
                if(empty($pass_sport)){
                  // $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
                  // $ajax_response->addCommand(new CssCommand('.productForm', ['display' => 'none']));
                  // $ajax_response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.productForm', 'css', array('background', '#dff0d8')));
                  
                  // $ajax_response->addCommand(new InvokeCommand('#edit-container-id-card', 'focus'));
      
                  # Commands Ajax - CssCommand()
                  $selector_input = '#container-id-card';
                  $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
                  $text_description = 'ข้อมูลนี้จำเป็น';
                  $selector_description = '#edit-container-left-id-card--description';
                  $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                  $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                  return $ajax_response;
                }else if(strlen($pass_sport) <= '5'){
                  $selector_input = '#container-id-card';
                  $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
                  $text_description = 'เลขหนังสือเดินทาง ไม่ถูกต้อง';
                  $selector_description = '#edit-container-left-id-card--description';
                  $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                  $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                  return $ajax_response;
                }else{
                  $data_check_exists_id_card = $this->store->get('data_check_exists_id_card');
                  if($data_check_exists_id_card['is_exists'] == 'Y'){
                    // มีบัญชี bigcard แล้ว
    
                    if($data_check_exists_id_card['is_online_register'] == 'Y'){
                      // YY
                      // ลงทะเบียนออนไลน์แล้ว
                      // แสดง link ไปหน้า login
                      $text = '<span style="color:red;font-size:23px;">คุณมีบัญชี บิ๊กการ์ด ออนไลน์แล้ว</span> <a href="/customer/account/login">Login</a>';
                      $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                      return $ajax_response;
                    }else{
                      // YN
                      // ยังไม่ลงทะเบียนออนไลน์
                      // ไปหน้าแสดงเบอร์ที่ผูกไว้

                      return $form;      
                    }
                  }else{
                    // NN
                    // ยังไม่มีบัญชี bigcard
                    // ไม่ให้สมัคร ให้สมัครที่ counter เท่านั้น
                    $text = '<span style="color:red;font-size:23px;">กรุณากรอกเลขบัตรประชาชน ต่างชาติให้ติดต่อ customer service</span>';
                    $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                    return $ajax_response;
                  }
                }
              break;
              }
            }
          break;
          }
          case 's2confirm':{
    
            $tel    = $form_state->getUserInput()['container1_tel'];
            // $dial_code    = $form_state->getUserInput()['container']['left']['dial_code'];

            // ----- test data ------
            // $text = '<span style="color:red;font-size:23px;">' . $dial_code .' > ' . $tel;
            // $ajax_response->addCommand(new HtmlCommand('#tel-description', $text));
            // return $ajax_response;

            if(empty($tel)){
              // $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
              // $ajax_response->addCommand(new CssCommand('.productForm', ['display' => 'none']));
              // $ajax_response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.productForm', 'css', array('background', '#dff0d8')));
              
              // $ajax_response->addCommand(new InvokeCommand('#edit-container-id-card', 'focus'));
    
              # Commands Ajax - CssCommand()
              $selector_input = '#container1-tel';
              $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
              $text_description = 'ข้อมูลนี้จำเป็น';
              $selector_description = '#tel-description';
              $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
              $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
              return $ajax_response;
            }

            if(!is_numeric($tel)){
              $selector_input = '#container1-tel';
              $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
              $text_description = 'เบอร์โทรศัพท์มือถือไม่ถูกต้อง';
              $selector_description = '#tel-description';
              $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
              $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
              return $ajax_response;
            }

            $data_send_otp = $this->store->get('data_send_otp');
            if($data_send_otp['code'] != '000'){
              // cannot send otp
              $selector_description = '#tel-description';
              $ajax_response->addCommand(new HtmlCommand($selector_description, 'ไม่สามารถส่งรหัส otp ได้'));
              $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
              return $ajax_response;
            }

            return $form;      
    
          break;
          }
          case 's3confirm':{
    
            $otp    = $form_state->getUserInput()['container2_otp'];
    
            if(empty($otp)){
              // $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
              // $ajax_response->addCommand(new CssCommand('.productForm', ['display' => 'none']));
              // $ajax_response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.productForm', 'css', array('background', '#dff0d8')));
              
              // $ajax_response->addCommand(new InvokeCommand('#edit-container-id-card', 'focus'));
    
              # Commands Ajax - CssCommand()
              $selector_input = '#container2-otp';
              $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
              $text_description = 'ข้อมูลนี้จำเป็น otp';
              $selector_description = '#otp-description';
              $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
              $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
            }else{
              $otp_number = $this->store->get('otp_number');
              $otp_expired_time = $this->store->get('otp_expired_time');
              if($otp_number == $otp && time() <= $otp_expired_time){
                // ใส่ OTP ถูกต้อง + otp ยังไม่หมดอายุ
                // ไปหน้า register
                $url = Url::fromRoute('new_member.step2');
                $command = new RedirectCommand($url->toString());
                $ajax_response->addCommand($command);
              }else{
                // ใส่ OTP ผิด
                // แสดงข้อความแจ้งเตือน
                $text_description = '<span style="color:red;font-size:20px">otp ไม่ถูกต้อง หรือหมดอายุ</span>';
                $selector_description = '#otp-description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
              }
            }
            return $ajax_response;
          break;
          }
          case 's3get_otp':{
            $selector_description = '#otp-description';
            // $text_description = 'ไม่สามารถได้ Resend เกินจำนวนครั้งที่กำหนด';
            $aaa = $this->store->get('data_send_otp');
            $text_description = json_encode($aaa);
            $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
            return $ajax_response;
          break;
          }
          case 's4confirm':{
            return $form;
          }
        }
      }

      //gpf or junior
      case 'gpf' :
      case 'junior':{

        $css_input = array(
          'border' => '1px dashed #eb340a',
          'background-color' => '#faebe7',
        );
        $css_description = array(
          'color' => 'red',
          'font-size' => '16px'
        );
        $css_clear = array(
          'border' => '1px solid #ced4da',
          'background-color' => 'transparent'
        );
        $css_clear_disable = array(
          'border' => '1px solid #ced4da',
          'background-color' => 'background-color: #e9ecef'
        );
    
        $btn_name   = $form_state->getTriggeringElement()['#name'];
        $ajax_response = new AjaxResponse();
    
        // reset all field
        $selector_input = '#container-id-card';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
        $selector_description = '#edit-container-left-id-card--description';
        $ajax_response->addCommand(new HtmlCommand($selector_description, ''));
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    
        $selector_input = '#container1-tel';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
        $ajax_response->addCommand(new HtmlCommand('#tel-description', ''));
    
        $selector_input = '#container2-otp';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
        $ajax_response->addCommand(new HtmlCommand('#otp-description', ''));
    
        // ---------------------------------------
    
        switch($btn_name){
          case 's1confirm':{
            
            // $select_type=  $form_state->getUserInput()['select_type'];
            // $this->store->set('select_type', $select_type);
    
            $user_input = $form_state->getUserInput()['container_id_card'];
    
            // 13 digit = (1) id card
            // others = (3) passport
            $select_type = (strlen($user_input) == '13') ? '1' : '3';
    
            // ----- test data ------
            // $text = '<span style="color:red;font-size:23px;">' . $btn_name . '</span><br>' . $user_input . '<br>' . strlen($user_input) . '<br>' . $select_type;
            // $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
            // return $ajax_response;
    
            switch($select_type){
              case '1':{
                $id_card = $user_input;
                // $id_card = $form_state->getUserInput()['container_id_card'];
    
                if(empty($id_card)){
                  $text_description = 'ข้อมูลนี้จำเป็น';
                  // $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
                  // $ajax_response->addCommand(new CssCommand('.productForm', ['display' => 'none']));
                  // $ajax_response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.productForm', 'css', array('background', '#dff0d8')));
                  
                  // $ajax_response->addCommand(new InvokeCommand('#edit-container-id-card', 'focus'));
      
                  # Commands Ajax - CssCommand()
                  $selector_input = '#container-id-card';
                  $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
                  $selector_description = '#edit-container-left-id-card--description';
                  $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                  $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                  return $ajax_response;
                }else if(!is_numeric($id_card) || strlen($id_card) != '13'){
                // }else if(strlen($id_card) != '13'){
                  $selector_input = '#container-id-card';
                  $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
                  $text_description = 'เลขบัตรประชาชน ไม่ถูกต้อง';
                  $selector_description = '#edit-container-left-id-card--description';
                  $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                  $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                  return $ajax_response;
                }else{
                  $data_check_exists_id_card = $this->store->get('data_check_exists_id_card');
                  if($data_check_exists_id_card['is_exists'] == 'Y'){
                    // มีบัญชี bigcard แล้ว
                    $data_find_member = $this->store->get('data_find_member');
                    \Drupal::logger('Bigcard')->notice($data_find_member['cardClass']);
                    // if($data_check_exists_id_card['is_online_register'] == 'Y'){
                    //   // YY
                    //   // ลงทะเบียนออนไลน์แล้ว
                    //   // แสดง link ไปหน้า login
                    //   $text = '<span style="color:red;font-size:23px;">คุณมีบัญชี บิ๊กการ์ด ออนไลน์แล้ว<span> <a href="/customer/account/login">Login</a>';
                    //   $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                    //   return $ajax_response;
                    // }else{
                    //   // YN
                    //   // ยังไม่ลงทะเบียนออนไลน์
                    //   // ไปหน้าแสดงเบอร์ที่ผูกไว้
                    // }
                    // if($data_find_member['cardClass'] == 'ONL' || $data_find_member['cardClass'] == 'ONL-SPO' || $data_find_member['cardClass'] == 'SLV'|| $data_find_member['cardClass'] == 'GOLD')
                    
                    if($data_check_exists_id_card['is_online_register'] == 'Y'){
                        //YY case ไม่ให้สมัคร

                        if($data_find_member['cardClass'] == 'BASF' || $data_find_member['cardClass'] == 'BACC' || $data_find_member['cardClass'] == 'WEL' || $data_find_member['cardClass'] == 'LOC' || $data_find_member['cardClass'] === '6') {
                          $text = '<span style="color:red;font-size:23px;">คุณเป็นสมาชิกบิ๊กการ์ดประเภทพิเศษแล้ว<br>หากต้องการลงทะเบียนเป็น bigcard Junior กรุณาติดต่อ<br>Call Center 1756';
                          $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                          return $ajax_response;
                        }elseif($data_find_member['cardClass'] == 'GPF'){
                            if($type == 'gpf')
                            {
                              //gpf
                              $text = '<span style="color:red;font-size:23px;">คุณมีบัญชีบิ๊กการ์ดออนไลน์แล้ว เข้าสู่ระบบได้ที่</span><span style="font-size:23px;"><a href="/customer/account/login"> "คลิ๊ก"</a></span>';
                              $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                              return $ajax_response;
                            }else
                            {
                              //junior
                              $text = '<span style="color:red;font-size:23px;">คุณเป็นสมาชิกบิ๊กการ์ดประเภทพิเศษแล้ว <br>หากต้องการลงทะเบียนเป็น bigcard GPF <br>Call Center 1756';
                              $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                              return $ajax_response;
                            }
                        }else if($data_find_member['cardClass'] == 'JUN'){
                            if($type == 'gpf')
                            {
                              //gpf
                              $text = '<span style="color:red;font-size:23px;">คุณเป็นสมาชิกบิ๊กการ์ดประเภทพิเศษแล้ว <br>หากต้องการลงทะเบียนเป็น bigcard junior <br>Call Center 1756';
                              $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                              return $ajax_response;
                            }else
                            {
                              //junior
                              $text = '<span style="color:red;font-size:23px;">คุณมีบัญชีบิ๊กการ์ดออนไลน์แล้ว เข้าสู่ระบบได้ที่</span><span style="font-size:23px;"><a href="/customer/account/login"> "คลิ๊ก"</a></span>';
                              $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                              return $ajax_response;
                            }
                        }else{
                            $text = '<span style="color:red;font-size:23px;">คุณมีบัญชี บิ๊กการ์ด ออนไลน์แล้ว</span><span style="font-size:23px;"><a href="/customer/account/login"> Login1</a></span>';
                            $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                            return $ajax_response;
                        }
                    }else
                    {
                      //YN

                      if( $data_find_member['cardClass'] == 'SILV' || $data_find_member['cardClass'] == 'ONL')
                      {
                        // OK can register
                      }elseif($data_find_member['cardClass'] == 'BASF' || $data_find_member['cardClass'] == 'BACC' || $data_find_member['cardClass'] == 'WEL' || $data_find_member['cardClass'] == 'LOC' || $data_find_member['cardClass'] === '6') {
                        $text = '<span style="color:red;font-size:23px;">คุณเป็นสมาชิกบิ๊กการ์ดประเภทพิเศษแล้ว<br>หากต้องการลงทะเบียนเป็น bigcard Junior กรุณาติดต่อ<br>Call Center 1756';
                        $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                        return $ajax_response;
                      }elseif($data_find_member['cardClass'] == 'GPF'){
                          if($type == 'gpf')
                          {
                            //gpf
                            $text = '<span style="color:red;font-size:23px;">คุณมีบัญชีบิ๊กการ์ดออนไลน์แล้ว เข้าสู่ระบบได้ที่</span><span style="font-size:23px;"><a href="/customer/account/login"> "คลิ๊ก"</a></span>';
                            $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                            return $ajax_response;
                          }else
                          {
                            //junior
                            $text = '<span style="color:red;font-size:23px;">คุณเป็นสมาชิกบิ๊กการ์ดประเภทพิเศษแล้ว <br>หากต้องการลงทะเบียนเป็น bigcard GPF <br> Call Center 1756';
                            $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                            return $ajax_response;
                          }
                      }else if($data_find_member['cardClass'] == 'JUN'){
                          if($type == 'gpf')
                          {
                            //gpf
                            $text = '<span style="color:red;font-size:23px;">คุณเป็นสมาชิกบิ๊กการ์ดประเภทพิเศษแล้ว <br>หากต้องการลงทะเบียนเป็น bigcard junior <br> Call Center 1756';
                            $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                            return $ajax_response;
                          }else
                          {
                            //junior
                            $text = '<span style="color:red;font-size:23px;">คุณมีบัญชีบิ๊กการ์ดออนไลน์แล้ว เข้าสู่ระบบได้ที่</span><span style="font-size:23px;"><a href="/customer/account/login"> "คลิ๊ก"</a></span>';
                            $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                            return $ajax_response;
                          }
                      }else{
                        $text = '<span style="color:red;font-size:23px;">คุณมีบัญชี บิ๊กการ์ด ออนไลน์แล้ว</span><span style="font-size:23px;"><a href="/customer/account/login"> Login1</a></span>';
                        $ajax_response->addCommand(new HtmlCommand('#edit-container-left-id-card--description', $text));
                        return $ajax_response;
                      }
                    }

                  }else{
                    // NN
                    // ยังไม่มีบัญชี bigcard
                    // ไปหน้ากรอกเบอร์โทร
                  }
                  return $form;                  
                }
    
              break;
              }
    
              case '3':{
                $text_description = 'เลขบัตรประชาชน ไม่ถูกต้อง';
                $selector_description = '#edit-container-left-id-card--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                return $ajax_response;
              break;
              }
            }
          break;
          }
          case 's2confirm':{
    
            $tel    = $form_state->getUserInput()['container1_tel'];
    
            if(empty($tel)){
              // $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
              // $ajax_response->addCommand(new CssCommand('.productForm', ['display' => 'none']));
              // $ajax_response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.productForm', 'css', array('background', '#dff0d8')));
              
              // $ajax_response->addCommand(new InvokeCommand('#edit-container-id-card', 'focus'));
    
              # Commands Ajax - CssCommand()
              $selector_input = '#container1-tel';
              $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
              $text_description = 'ข้อมูลนี้จำเป็น';
              $selector_description = '#tel-description';
              $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
              $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
              return $ajax_response;
            }
    
          break;
          }
          case 's3confirm':{
    
            $otp    = $form_state->getUserInput()['container2_otp'];
    
            if(empty($otp)){
              // $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
              // $ajax_response->addCommand(new CssCommand('.productForm', ['display' => 'none']));
              // $ajax_response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.productForm', 'css', array('background', '#dff0d8')));
              
              // $ajax_response->addCommand(new InvokeCommand('#edit-container-id-card', 'focus'));
    
              # Commands Ajax - CssCommand()
              $selector_input = '#container2-otp';
              $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
              $text_description = 'ข้อมูลนี้จำเป็น otp';
              $selector_description = '#otp-description';
              $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
              $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
            }else{
              $otp_number = $this->store->get('otp_number');
              $otp_expired_time = $this->store->get('otp_expired_time');
              if($otp_number == $otp && time() <= $otp_expired_time){
                // ใส่ OTP ถูกต้อง + otp ยังไม่หมดอายุ
                // ไปหน้า register
                $url = Url::fromRoute('new_member.step2');
                $command = new RedirectCommand($url->toString());
                $ajax_response->addCommand($command);
              }else{
                // ใส่ OTP ผิด
                // แสดงข้อความแจ้งเตือน
                $text_description = '<span style="color:red;font-size:20px">otp ไม่ถูกต้อง หรือหมดอายุ</span>';
                $selector_description = '#otp-description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
              }
            }
            return $ajax_response;
          break;
          }
          case 's3get_otp':{
            // $selector_description = '#otp-description';
            // $text_description = 'ไม่สามารถได้ Resend เกินจำนวนครั้งที่กำหนด';
            // $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
            // $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
            // return $ajax_response;
          break;
          }
        }


      break;
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $type = $this->store->get('type');
    // $this->store->set('email', $form_state->getValue('email'));
    // $this->store->set('name', $form_state->getValue('name'));
    $form_state->setRedirect('new_member.step2');
  }
}
