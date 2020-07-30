<?php

/**
 * @file
 * Contains \Drupal\demo\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\bigcard\Form\MultistepNewMember;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Url;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;


use Drupal\bigcard\Utils\Utils;

class Step3PasswordForm extends MultistepFormBase {
  
  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'step_3_password_form';
  }

  public function get_wizard(){
    return '<div class="register_steps">
              <div class="main-wrapper">
                  <div id="signup_steps">
                      <ul>
                          <li style="float: left; width: 25%;" id="step_check" class="writing">
                              <i class="far fa-check-circle opaque"></i>
                              <a href="./step1'.$this->store->get('type').'" title="' . $this->t('ตรวจสอบข้อมูล') . '"><span>1. </span>' . $this->t('ตรวจสอบข้อมูล') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_signup" class="pending">
                              <i class="far fa-check-circle opaque"></i>
                              <a href="./step2" title="' . $this->t('ข้อมูลสำหรับเข้าสู่ระบบ') . '"><span>2. </span>' . $this->t('ข้อมูลสำหรับเข้าสู่ระบบ') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_password" class="pending">
                              <i class="fas fa-pencil-alt pencil-icon opaque"></i>
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

    $form['#tree'] = TRUE;


    $form['container'] = array(
      '#type' => 'container',
      // '#prefix' => '<div id="container">
      //                 <div class="row">
      //                   <div class="col-12">
      //                   ' .  $this->get_wizard() . '
      //                   </div>
      //                 </div>
      //                 <div class="row container-px">',
      // '#suffix' => '</div>',
    );

    
    $form['container']['header'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="container1">
                      <div class="row">
                        <div class="col-12">
                        ' .  $this->get_wizard() . '
                        </div>
                      </div>
                      <div class="row container-px">'
    );
    

    dpm($this->store->get('data_find_member'));
    dpm('title ' . $this->store->get('title'));


    $form['container']['left'] = array(
      '#type' => 'container',
      '#prefix' => '<div id="container1-left" class="col-lg-6 col-md-6 col-sm-12 col-12">
                      <h3 class="title_head">' . $this->t('กรุณาตั้งรหัสผ่าน เพื่อใช้เข้าสู่ระบบ') . '</h3>',
      '#suffix' => '</div>',
    );

    $form['container']['left']['guide_text'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="d-inline-block fs-xs">
                      ' . $this->t('เงื่อนไขการสร้างรหัสผ่าน') . '
                      <div class="line-bottom">&nbsp;</div>
                    </div>
                    <div class="fs-xs">
                      <ul class="create-password-desc">
                        <li>' . $this->t('ประกอบด้วยตัวอักษร A-Z อย่างน้อย 1 ตัว') . '</li>
                        <li>' . $this->t('ประกอบด้วยตัวอักษร a-z อย่างน้อย 1 ตัว') . '</li>
                        <li>' . $this->t('มีตัวเลข 0-9 อย่างน้อย 1 ตัว') . '</li>
                        <li>' . $this->t('ความยาวของรหัสผ่านอย่างน้อย 6 ตัวอักษร') . '</li>
                      </ul>
                    </div>'
    );

    $form['container']['left']['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('รหัสผ่าน'),
      // '#default_value' => $this->store->get('tel'),
      '#description'=>$this->t(''),
      '#prefix' => '',
      '#suffix' => '',
    );

    $form['container']['left']['confirm_password'] = array(
      '#type' => 'password',
      '#title' => $this->t('ยืนยันรหัสผ่าน'),
      // '#default_value' => $this->store->get('tel'),
      '#description'=>$this->t(''),
      '#prefix' => '',
      '#suffix' => '',
    );

    $form['container']['left']['captcha'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('กรอกตัวอักษรที่แสดงในกล่องข้างล่าง'),
      // '#default_value' => $this->store->get('tel'),
      '#description'=>$this->t(''),
      '#prefix' => '',
      '#suffix' => '',
    );

    $form['container']['left']['c'] = array(
      '#type' => 'container',
      // '#title' => $this->t('กรุณากรอกข้อมูลให้ครบ'),
      '#prefix' => '<div id="img_captcha">',
      '#suffix' => '</div>',
    );

    $this->captchaBuilder->build();
    // $this->store->set('img_captcha', $this->captchaBuilder->getPhrase());
    // dpm($this->store->get('img_captcha'));

    // $_SESSION['img_captcha'] = $this->captchaBuilder->getPhrase();
    // dpm($_SESSION['img_captcha']);

    $form['container']['left']['c']['img_captcha'] = array(
      '#type'     =>'markup',
      '#markup'   =>'<img src="' . Utils::base64ToImage($this->captchaBuilder->inline())  . '" alt="picture">',
      '#prefix' => '',
      '#suffix' => '',               
    );

    $form['container']['left']['c']['hidden'] = array(
      '#type' => 'hidden',
      '#value' => base64_encode(base64_encode($this->captchaBuilder->getPhrase())) ,
    );

    $form['container']['left']['refresh'] = array(
      '#type' => 'submit',
      '#name' => 'refresh',
      '#value' => html_entity_decode('&#xf2f1;'),
      '#submit' => ['::submitAjax'],
      '#ajax' => [
        'callback' => '::callbackAjax',
        'wrapper' => 'img_captcha',
        'progress' => array(
            // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
            'type' => 'throbber',
            // Message to show along progress graphic. Default: 'Please wait...'.
            'message' => NULL,
        ),
      ],
    );
    
    $form['container']['left']['submit'] = array(
      '#type' => 'submit',
      '#name' => 's3confirm',
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

    $form['container']['right'] = array(
      '#type' => 'container',
      '#prefix' => '<div id="container1-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
      '#suffix' => '</div></div></div></div>',
     
    );

    $form['container']['right']['text'] = array(
      '#type' => 'markup',
      '#markup' => Utils::getTextRight(),
     
    );

    return $form;
  }

  public function submitAjax(array &$form, FormStateInterface $form_state) {
    // drupal_set_message("s1confirm_form_submit.");

    $btn_name   = $form_state->getTriggeringElement()['#name'];

    switch($btn_name){
      case 'refresh':{

      break;
      }
      case 's3confirm':{
        
        $password_information = $form_state->getUserInput()['container']['left'];
        $this->store->set('password', $password_information['password']);
        $this->store->set('confirm_password', $password_information['confirm_password']);
        $this->store->set('captcha', $password_information['captcha']);

        // $img_captcha = $this->store->get('img_captcha');
        $img_captcha = $form_state->getUserInput()['container']['left']['c']['hidden'];
        $img_captcha = base64_decode(base64_decode($img_captcha));
        $captcha = $password_information['captcha'];

        $this->store->set('is_valid_captcha', FALSE);
        if(strcasecmp($img_captcha, $captcha) == 0){
          $this->store->set('is_valid_captcha', TRUE);
          // $form_state->setRebuild();
        }
      break;
      }
    }
    $form_state->setRebuild();
  }

  public function callbackAjax(array &$form, FormStateInterface $form_state) {
    $btn_name   = $form_state->getTriggeringElement()['#name'];
    $ajax_response = new AjaxResponse();
    $type = $this->store->get('type');

    switch($btn_name){
      case 'refresh':{
        return  $form['container']['left']['c'];
      break;
      }
      case 's3confirm':{
        $pass = TRUE;

        $text_description = $this->t('ข้อมูลนี้จำเป็น');
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

        // reset all field
        $selector_input = '#edit-container-left-password';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
        $selector_description = '#edit-container-left-password--description';
        $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

        $selector_input = '#edit-container-left-confirm-password';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
        $selector_description = '#edit-container-left-confirm-password--description';
        $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

        $selector_input = '#edit-container-left-captcha';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
        $selector_description = '#edit-container-left-captcha--description';
        $ajax_response->addCommand(new HtmlCommand($selector_description, ''));
        // --------------------------------------------
        
        $password = $this->store->get('password');
        $confirm_password = $this->store->get('confirm_password');
        $captcha = $this->store->get('captcha');
        $img_captcha = $form_state->getUserInput()['container']['left']['c']['hidden'];
        
        if(empty($password)){
          $pass = FALSE;
          $selector_input = '#edit-container-left-password';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
          $selector_description = '#edit-container-left-password--description';
          $text_description =  $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
    
        if(empty($confirm_password)){
          $pass = FALSE;
          $selector_input = '#edit-container-left-confirm-password';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
          $selector_description = '#edit-container-left-confirm-password--description';
          $text_description =  $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
    
        if(empty($captcha)){
          $pass = FALSE;
          $selector_input = '#edit-container-left-captcha';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
          $selector_description = '#edit-container-left-captcha--description';
          $text_description =  $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

        if(!empty($password) && !empty($confirm_password)){
          $uppercase = preg_match('@[A-Z]@', $password);
          $lowercase = preg_match('@[a-z]@', $password);
          $number    = preg_match('@[0-9]@', $password);

          if(($password != $confirm_password) || !$uppercase || !$lowercase || !$number || strlen($password)<6){
            $pass = FALSE;
            $selector_input = '#edit-container-left-password';
            $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
      
            $selector_description = '#edit-container-left-password--description';
            $text_description = '-ประกอบด้วยตัวอักษร A-Z และ a-z อย่างละ 1 ตัวขึ้นไป
            <br>-มีตัวเลข 0-9 อย่างน้อย 1 ตัวขึ้นไป
            <br>-มีความยาวร่วมกัน 6 ตัวขึ้นไป
            <br>-ห้ามมีอักษรภาษาไทยและเว้นวรรค
            <br>-ตัวอย่าง A1234a, ABCDe1';
            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
            $ajax_response->addCommand(new CssCommand($selector_description, $css_description));

            $selector_input = '#edit-container-left-confirm-password';
            $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
      
            $selector_description = '#edit-container-left-confirm-password--description';
            $text_description = 'โปรดตรวจสอบให้แน่ใจว่ารหัสผ่านของคุณตรงกัน';
            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
            $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
          }
        }

        $is_valid_captcha = $this->store->get('is_valid_captcha');
        if(!$is_valid_captcha){
          $pass = FALSE;
          $selector_input = '#edit-container-left-captcha';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
    
          $selector_description = '#edit-container-left-captcha--description';
          $text_description =  $this->t('ตัวอักษรไม่ถูกต้อง');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

        if($pass){
          $data_register = $this->prepare_register_info($form,$form_state);

          // $selector_description = '#edit-container-left-captcha--description';
          // $test_register = $this->store->get('test_register');
          // $text_description = json_encode($data_register) . ' ' . json_encode($test_register) ;
          // $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          // $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
          // return $ajax_response;

          if($data_register['code'] == '200'){
            if(!empty($this->store->get('change_card')['code']) && $type != 'normal')
            {
              // case gpf, junior
              \Drupal::logger('jieb')->notice('change_card'.$this->store->get('change_card')['code']);
              if($this->store->get('change_card')['code'] == '200')
              {
                $url = Url::fromRoute('new_member.step4');
                $command = new RedirectCommand($url->toString());
                $ajax_response->addCommand($command);
              }else
              {
                $selector_description = '#edit-container-left-captcha--description';
                $test_register = $this->store->get('test_register');
                $text_description = $this->t('can not change card');
                $text_description = $this->store->get('change_card')['code'];
                $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
              }
            }else
            {
            // normal
              $url = Url::fromRoute('new_member.step4');
              $command = new RedirectCommand($url->toString());
              $ajax_response->addCommand($command);
            }
          }else{
            $selector_description = '#edit-container-left-captcha--description';
            $test_register = $this->store->get('test_register');
            $text_description = $this->t('Registration fail');
            $text_description = json_encode($data_register) . ' ' . json_encode($test_register);
            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
            $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
          }
        }

        return $ajax_response;
      break;
      }
    }
  }

  public function prepare_register_info(array &$form, FormStateInterface $form_state) {
    $type = $this->store->get('type');

    $is_foreigner = $this->store->get('is_foreigner');

    // Nationality: 1=Thai, 999=Other
    $data_find_member = $this->store->get('data_find_member');
    $nationality = empty($data_find_member['nationality']) ? '1' : $data_find_member['nationality'];
    $bigcard = $data_find_member['bigcard'];

    $data_check_exists_id_card = $this->store->get('data_check_exists_id_card');
    $is_exists = $data_check_exists_id_card['is_exists'];
    $is_online_register = $data_check_exists_id_card['is_online_register'];

    // ----------------------------------------------------------
    
    $dial_code = $this->store->get('dial_code');
    $tel = $this->store->get('tel');

    // $bigcard = ''; // Optional
    $issued_store = $this->store->get('issued_store'); // Optional
    $mobile_phone = ($dial_code == '66') ? ('0' . $tel) : ($dial_code . $tel);

    $id_card_type = $this->store->get('select_type'); // 1=ID Card, 2=Driver License, 3=Passport (Mandatory: when new member Optional: when existing member)
    $id_card = ($id_card_type == '1') ? $this->store->get('id_card') : $this->store->get('passport');
    // $id_card_ref = ''; // Optional
    
    $email = $this->store->get('email'); // Optional
    $title = $this->store->get('title'); // Optional
    $t_name = $this->store->get('name');
    $t_last_name = $this->store->get('last_name');
    $e_name = $this->store->get('name'); // Optional
    $e_last_name = $this->store->get('last_name'); // Optional
    $gender = $this->store->get('gender');
    $birth_date = $this->store->get('birth_date');
    $nationality = $nationality; // Nationality: 1=Thai, 999=Other (Mandatory: when new member Optional: when existing member)
    // $nationality_other = ''; // Optional
    $add_type = $this->store->get('add_type'); // Address Type: 1=Domestic, 5=Overseas
    $address1 = $this->store->get('address1');
    $address2 = $this->store->get('address2'); // Optional
    $moo = $this->store->get('moo'); // Optional
    $room_no = $this->store->get('room_no'); // Optional
    $soi = $this->store->get('soi'); // Optional
    $road = $this->store->get('road'); // Optional
    $sub_district = $this->store->get('sub_district');
    $district = $this->store->get('district');
    $province = $this->store->get('province');
    $postal_code = $this->store->get('postal_code');
    // $postal_code = ($nationality_type == '1') ? $this->store->get('postal_code') : $this->store->get('postal_code_foreigner');
    // $country = 'TH'; // Optional
    $country = ($add_type == '1') ? 'TH' : $this->store->get('country');
    $home_phone = $this->store->get('home_phone'); // Optional
    $home_phone_ext = $this->store->get('home_phone_ext'); // Optional
    $contact_permission = '2'; // Contact Permission: 1=Allow, 2=Not Allow
    $contact_preference = '0'; // Contact Preference: 0=Not Allow, 1=Email, 2=Mobile, 3=Email+Mobile, 4=Mail, 5=Email+Mail, 6=Mobile+Mail, 7=Email+Mobile+Mail
    // $language = 'TH';
    $language = $this->store->get('language');
    $occupation = $this->store->get('occupation'); // Optional
    $welfare_id = $this->store->get('welfare_id'); // Optional
    $password = $this->store->get('password');
    // $pin = ''; // Optional
    // $consent_status = ''; // Optional Consent Status: C=Consent, U=Unconsent, N=Not Now
    // $consent_otp_ref = ''; // Optional
    // $consent_otp = ''; // Optional
    // $isVerifyDopa = ''; // Optional

  if($type == 'normal'){
    if($is_exists == 'Y' && $is_online_register == 'N'){
      // YN
      if($is_foreigner){
        // Passport + YN + nationality 999
        $data_obj = [
          'bigcard' => $bigcard,
          'issuedStore' => $issued_store,
          'mobilePhone' => $mobile_phone,
          'idCard' => $id_card,
          // 'idCardRef' => $id_card_ref,
          'idCardType' => $id_card_type,
          'email' => $email,
          'title' => $title,
          'tName' => $t_name,
          'tLastName' => $t_last_name,
          'eName' => $e_name,
          'eLastName' => $e_last_name,
          'gender' => $gender,
          'birthDate' => $birth_date,
          'nationality' => $nationality,
          // 'nationalityOther' => $nationality_other,
          'addType' => $add_type,
          'address1' => $address1,
          'address2' => $address2,
          'moo' => $moo,
          'roomNo' => $room_no,
          'soi' => $soi,
          'road' => $road,
          'subDistrict' => $sub_district,
          'district' => $district,
          'province' => $province,
          'postalCode' => $postal_code,
          'country' => $country,
          'homePhone' => $home_phone,
          'homePhoneExt' => $home_phone_ext,
          'contactPermission' => $contact_permission,
          'contactPreference' => $contact_preference,
          'language' => $language, // not in manual doc
          'occupation' => $occupation,
          'welfareId' => $welfare_id,
          'password' => $password,
          // 'pin' => $pin,
          // 'consentStatus' => $consent_status,
          // 'consentOtpRef' => $consent_otp_ref,
          // 'consentOtp' => $consent_otp,
          // 'isVerifyDopa' => $is_verify_dopa,
          ];
      }else{
        // ID Card + YN / Passport + YN + nationality 1
        $data_obj = [
          'bigcard' => $bigcard,
          'issuedStore' => $issued_store,
          'mobilePhone' => $mobile_phone,
          'idCard' => $id_card,
          // 'idCardRef' => $id_card_ref,
          'idCardType' => $id_card_type,
          'email' => $email,
          'title' => $title,
          'tName' => $t_name,
          'tLastName' => $t_last_name,
          'eName' => $e_name,
          'eLastName' => $e_last_name,
          'gender' => $gender,
          'birthDate' => $birth_date,
          'nationality' => $nationality,
          // 'nationalityOther' => $nationality_other,
          'addType' => '1',
          'address1' => $address1,
          'address2' => $address2,
          'moo' => $moo,
          'roomNo' => $room_no,
          'soi' => $soi,
          'road' => $road,
          'subDistrict' => $sub_district,
          'district' => $district,
          'province' => $province,
          'postalCode' => $postal_code,
          'country' => 'TH',
          'homePhone' => $home_phone,
          'homePhoneExt' => $home_phone_ext,
          'contactPermission' => $contact_permission,
          'contactPreference' => $contact_preference,
          'language' => $language, // not in manual doc
          'occupation' => $occupation,
          'welfareId' => $welfare_id,
          'password' => $password,
          // 'pin' => $pin,
          // 'consentStatus' => $consent_status,
          // 'consentOtpRef' => $consent_otp_ref,
          // 'consentOtp' => $consent_otp,
          // 'isVerifyDopa' => $is_verify_dopa,
          ];
      }
      
    }else{
      // ID Card + NN
      $data_obj = [
        // 'bigcard' => $bigcard,
        'issuedStore' => $issued_store,
        'mobilePhone' => $mobile_phone,
        'idCard' => $id_card,
        // 'idCardRef' => $id_card_ref,
        'idCardType' => $id_card_type,
        'email' => $email,
        'title' => $title,
        'tName' => $t_name,
        'tLastName' => $t_last_name,
        'eName' => $e_name,
        'eLastName' => $e_last_name,
        'gender' => $gender,
        'birthDate' => $birth_date,
        'nationality' => $nationality,
        // 'nationalityOther' => $nationality_other,
        'addType' => '1',
        'address1' => $address1,
        'address2' => $address2,
        'moo' => $moo,
        'roomNo' => $room_no,
        'soi' => $soi,
        'road' => $road,
        'subDistrict' => $sub_district,
        'district' => $district,
        'province' => $province,
        'postalCode' => $postal_code,
        'country' => 'TH',
        'homePhone' => $home_phone,
        'homePhoneExt' => $home_phone_ext,
        'contactPermission' => $contact_permission,
        'contactPreference' => $contact_preference,
        'language' => $language, // not in manual doc
        'occupation' => $occupation,
        'welfareId' => $welfare_id,
        'password' => $password,
        // 'pin' => $pin,
        // 'consentStatus' => $consent_status,
        // 'consentOtpRef' => $consent_otp_ref,
        // 'consentOtp' => $consent_otp,
        // 'isVerifyDopa' => $is_verify_dopa,
        ];
    }
  }else{
    if($is_exists == 'Y' && $is_online_register == 'N'){
      $data_obj = [
        'bigcard' => $bigcard,
        // 'issuedStore' => $issued_store,
        'mobilePhone' => $mobile_phone,
        'idCard' => $id_card,
        // 'idCardRef' => $id_card_ref,
        'idCardType' => '1',
        'email' => $email,
        'title' => $title,
        'tName' => $t_name,
        'tLastName' => $t_last_name,
        'eName' => $e_name,
        'eLastName' => $e_last_name,
        'gender' => $gender,
        'birthDate' => $birth_date,
        'nationality' => '1',
        // 'nationalityOther' => $nationality_other,
        'addType' => '1',
        'address1' => $address1,
        'address2' => $address2,
        'moo' => $moo,
        'roomNo' => $room_no,
        'soi' => $soi,
        'road' => $road,
        'subDistrict' => $sub_district,
        'district' => $district,
        'province' => $province,
        'postalCode' => $postal_code,
        'country' => 'TH',
        'homePhone' => $home_phone,
        'homePhoneExt' => $home_phone_ext,
        'contactPermission' => $contact_permission,
        'contactPreference' => $contact_preference,
        'language' => $language, // not in manual doc
        'occupation' => $occupation,
        // 'welfareId' => $welfare_id,
        'password' => $password,
        // 'pin' => $pin,
        // 'consentStatus' => $consent_status,
        // 'consentOtpRef' => $consent_otp_ref,
        // 'consentOtp' => $consent_otp,
        // 'isVerifyDopa' => $is_verify_dopa,
        ];
    }else{
      //gpf, junior
    $data_obj = [
      // 'bigcard' => $bigcard,
      //'issuedStore' => $issued_store,
      'mobilePhone' => $mobile_phone,
      'idCard' => $id_card,
      // 'idCardRef' => $id_card_ref,
      'idCardType' => $id_card_type,
      'email' => $email,
      'title' => $title,
      'tName' => $t_name,
      'tLastName' => $t_last_name,
      'eName' => $e_name,
      'eLastName' => $e_last_name,
      'gender' => $gender,
      'birthDate' => $birth_date,
      'nationality' => $nationality,
      // 'nationalityOther' => $nationality_other,
      'addType' => '1',
      'address1' => $address1,
      'address2' => $address2,
      'moo' => $moo,
      'roomNo' => $room_no,
      'soi' => $soi,
      'road' => $road,
      'subDistrict' => $sub_district,
      'district' => $district,
      'province' => $province,
      'postalCode' => $postal_code,
      'country' => 'TH',
      'homePhone' => $home_phone,
      'homePhoneExt' => $home_phone_ext,
      'contactPermission' => $contact_permission,
      'contactPreference' => $contact_preference,
      'language' => $language, // not in manual doc
      'occupation' => $occupation,
      //'welfareId' => $welfare_id,
      'password' => $password,
      // 'pin' => $pin,
      // 'consentStatus' => $consent_status,
      // 'consentOtpRef' => $consent_otp_ref,
      // 'consentOtp' => $consent_otp,
      // 'isVerifyDopa' => $is_verify_dopa,
      ];
    }
    
  }
  
  
    dpm($data_obj);
    $this->store->set('test_register', $data_obj);

    // register
    $data_register = Utils::register_api($data_obj);
    $this->store->set('data_register', $data_register);
    // dpm($data_register);

    //  if registration is success
    if($data_register['code'] == '200'){
      // send sms new member
      $bigcard = $this->store->get('data_register')['bigcard'];
      $bigcard_no = Utils::formatted_bigcard_number($bigcard);
      $data_sms_new_member = Utils::sms_new_member_api($mobile_phone, $bigcard_no, $language);
      $this->store->set('data_sms_new_member', $data_sms_new_member);

      // send sms welcome pack
      $data_sms_welcome_pack = Utils::sms_welcome_pack_api($mobile_phone);
      $this->store->set('data_sms_welcome_pack', $data_sms_welcome_pack);

      //changeCard
      if($type == 'gpf')
      {
        $change_card = Utils::change_card_api($type,$bigcard);
        $this->store->set('change_card', $change_card);
      }elseif($type == 'junior')
      {
        $change_card = Utils::change_card_api('jun',$bigcard);
        $this->store->set('change_card', $change_card);
      }
    }
    
    
    $form_state->setRebuild();

    return $data_register;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $this->store->set('age', $form_state->getValue('age'));
    // $this->store->set('location', $form_state->getValue('location'));

    // // Save the data
    parent::deleteStore(['name','last_name']);
    // $form_state->setRedirect('some_route');

  }
}
