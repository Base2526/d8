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
use PHP_CodeSniffer\Standards\PSR2\Sniffs\ControlStructures\ElseIfDeclarationSniff;

class Step2LoginInformationForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'step_2_login_information_form';
  }

  public function get_wizard(){
    return '<div class="register_steps">
              <div class="main-wrapper">
                  <div id="signup_steps">
                      <ul>
                          <li style="float: left; width: 25%;" id="step_check" class="writing">
                              <i class="far fa-check-circle opaque"></i>

                              <a href="./step1/'.$this->store->get('type').'" title="' . $this->t('ตรวจสอบข้อมูล') . '"><span>1. </span>' . $this->t('ตรวจสอบข้อมูล') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_signup" class="pending">
                              <i class="fas fa-pencil-alt pencil-icon opaque"></i>
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

    $keys = ['select_type', 'id_card', 'pass_sport', 'tel'];
    foreach ($keys as $key) {
      $v =  $this->store->get($key);
      // dpm($v);
    }

    $values = $form_state->getValues();
    $type = $this->store->get('type');

    $form['#tree'] = TRUE;

    $form['container'] = array(
      '#type' => 'container',
      // '#prefix' => '<div class="row">
      //                   <div class="col-12">
      //                     ' . $this->get_wizard() . '
      //                   </div>
      //                 </div>',
      // '#suffix' => '</div>',
    );

    dpm($this->store->get('data_find_member'));
    dpm( 'is_foreigner '. $this->store->get('is_foreigner'));

    $form['container']['stepper'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="row">
                      <div class="col-12">
                        ' . $this->get_wizard() . '
                      </div>
                    </div>'
    );

    $form['container']['left'] = array(
      '#type' => 'container',
      '#prefix' => '<div class="row container-px">
                      <div id="container-left" class="col-lg-6 col-md-6 col-sm-12 col-12">',
      '#suffix' => '</div>',
    );

    if($type == 'normal')
    {
      $form['container']['left']['branch_register'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('สาขาที่สมัคร'),
        '#default_value' => $this->store->get('age') ? $this->store->get('age') : '',
        '#description' => $this->t(''),
      );
    }

    $form['container']['left']['s1'] = array(
      '#type' => 'container',
      // '#title' => $this->t('กรุณากรอกข้อมูลให้ครบ'),
      '#prefix' => '<div id="container" class="pt-1">
                      <h3 class="title_head">' . $this->t('กรุณากรอกข้อมูลให้ครบ') . '</h3>',
      '#suffix' => '</div>',
    );

    $form['container']['left']['s1']['personal_information'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('ข้อมูลส่วนตัว'),
      '#open' => TRUE,
    );

    if($type == 'normal')
    {

      $form['container']['left']['s1']['personal_information']['welfare_id'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('บัตรสวัสดิการแห่งรัฐ'),
        '#description' => $this->t(''),
      );

      $select_type = $this->store->get('select_type');
      if($select_type == '1'){
        $form['container']['left']['s1']['personal_information']['id_card'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('เลขบัตรประชาชน'),
          '#value' => $this->store->get('id_card'),
          '#description' => $this->t(''),
          '#attributes'=>array('readonly' => 'readonly'),
        );
    
        $form['container']['left']['s1']['personal_information']['laser'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('รหัสหลังบัตรประชาชน'),
          '#description' => $this->t(''),
        );
      }else{
        $form['container']['left']['s1']['personal_information']['passport'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('เลขหนังสือเดินทาง'),
          '#value' => $this->store->get('passport'),
          '#description' => $this->t(''),
          '#attributes'=>array('readonly' => 'readonly'),
        );
      }

    }
    
    $form['container']['left']['s1']['personal_information']['prefix_name'] = array(
      '#type' => 'select',
      '#title' => $this->t('คำนำหน้า'),
      '#empty_option' => '- เลือก -',
      '#options'      => Utils::getTaxonomy_Term('prefix_name'),
    );

    $form['container']['left']['s1']['personal_information']['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('ชื่อ') . ' *',
      '#description' => $this->t(''),
      '#prefix' => '<div class="row">
                      <div class="col-6 pl-0 pr-1">',
      '#suffix' => '</div>'
    );

    $form['container']['left']['s1']['personal_information']['last_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('นามสกุล') . ' *',
      '#description' => $this->t(''),
      '#prefix' => '<div class="col-6 pl-1 pr-0">',
      '#suffix' => '</div></div>'
    );

    $form['container']['left']['s1']['personal_information']['gender'] = array(
        '#type'         => 'radios',
        '#title'        => $this->t('เพศ') . ' *',
        '#options'      => Utils::getTaxonomy_Term('sex'),
        '#description'  => $this->t(''),
    );


    if($type == 'normal' ||  $type == 'gpf')
    {
      $form['container']['left']['s1']['personal_information']['occupation'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('อาชีพ'),
      );
    }else
    {
      // type = junior
      $form['container']['left']['s1']['personal_information']['occupation'] = array(
        '#type' => 'select',
        '#title' => $this->t('อาชีพ'),
        '#empty_option' => '- เลือก -',
        '#options'      => Utils::getTaxonomy_Term('occupation'),
        '#description' => $this->t(''),
      );
    }

    $form['container']['left']['s1']['personal_information']['bd'] = array(
      '#type' => 'container',
      '#prefix' => '<div id="db" class="fs-xs">' . $this->t('วันเกิด') . ' *',
      '#suffix' => '<div id="db-message"></div></div>',
    );

    $form['container']['left']['s1']['personal_information']['bd']['date_of_birth'] = array(
      '#type' => 'select',
      '#empty_option' => '- เลือก -',
      '#options'      => Utils::getTaxonomy_Term('dates'),
      '#description' => $this->t(''),
      '#prefix' => '<div class="row">
                      <div class="col-4 pl-0 pr-1">',
      '#suffix' => '</div>'
    );

    $form['container']['left']['s1']['personal_information']['bd']['month_of_birth'] = array(
      '#type' => 'select',
      '#empty_option' => '- เลือก -',
      '#options'      => Utils::getTaxonomy_Term('month'),
      '#description' => $this->t(''),
      '#prefix' => '<div class="col-4 pl-1 pr-1">',
      '#suffix' => '</div>'
    );

    $form['container']['left']['s1']['personal_information']['bd']['year_of_birth'] = array(
      '#type' => 'select',
      '#empty_option' => '- เลือก -',
      '#options'      => Utils::getTaxonomy_Term('year'),
      '#description' => $this->t(''),
      '#prefix' => '<div class="col-4 pl-1 pr-0">',
      '#suffix' => '</div></div>'
    );

    $tel = $this->store->get('tel');
    $full_tel = Utils::getDisplayMobilePhone($tel);
    $form['container']['left']['s1']['personal_information']['tel'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
      '#value' => $full_tel,
      '#attributes'=>array('readonly' => 'readonly')
    );

    $form['container']['left']['s1']['personal_information']['email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('อีเมล'),
      '#description' => $this->t(''),
      // '#value' => $this->store->get('email')
    );

    $form['container']['left']['s1']['personal_information']['tel_home'] = array(
      '#type' => 'container',
      '#prefix' => '<div id="db" class="fs-xs">' . $this->t('เบอร์โทรศัพท์บ้าน'),
      '#suffix' => '</div>',
    );

    $form['container']['left']['s1']['personal_information']['tel_home']['home_phone'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="row">
                      <div class="col-9 pl-0 pr-1">',
      '#suffix' => '</div>'
    );

    $form['container']['left']['s1']['personal_information']['tel_home']['txt'] = array(
      '#type' => 'item',
      '#markup' => $this->t('ต่อ'),
      '#prefix' => '<div class="col-1 pl-1 pr-1 fs-xs">',
      '#suffix' => '</div>'
    );

    $form['container']['left']['s1']['personal_information']['tel_home']['ext'] = array(
      '#type' => 'textfield',
      '#prefix' => '<div class="col-2 pl-1 pr-0">',
      '#suffix' => '</div></div><hr>'
    );

    $form['container']['left']['s1']['personal_address'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('ที่อยู่ที่ติดต่อได้'),
      '#open' => TRUE,
      '#prefix' => '<div id="personal_address">',
      '#suffix' => '</div>'
    );


    if($type == 'normal')
    {
        $is_foreigner = $this->store->get('is_foreigner');

        $data_find_member = $this->store->get('data_find_member');
        // $nationality = $data_find_member['nationality'];

        // Passport + YN + Nationality 999
        if($is_foreigner){
          // 1=Domestic, 5=Overseas
          $form['container']['left']['s1']['personal_address']['add_type'] = array(
            '#type' => 'radios',
            // '#title' => t('เลือก'),
            '#default_value' => 1,
            '#options' => array(1=>'ประเทศไทย', 5=>'อื่นๆ'),
            '#attributes' => [
              'id' => 'select-add-type',
              'name' => 'select_add_type',
            ],
          );

          $form['container']['left']['s1']['personal_address']['country_foreigner'] = array(
            '#type' => 'select',
            '#empty_option' => '- เลือก -',
            '#title' => $this->t('ประเทศ *'),
            '#description'  => $this->t(''),
            '#options'      => Utils::getTaxonomy_term('countries'),
            '#states' => [
              //show this textfield only if the radio 'other' is selected above
              'visible' => [
                ':input[name="select_add_type"]' => ['value' => 5],
              ],
            ],
            '#attributes' => [
              'id' => 'select-country',
              'name' => 'select_country',
            ],
          );
        }
    }

    $form['container']['left']['s1']['personal_address']['address1'] = array(
      '#title' => $this->t('บ้านเลขที่') . ' *',
      '#type' => 'textfield',
      '#description'  => $this->t(''),
      '#prefix' => '<div class="row">
                      <div class="col-6 pl-0 pr-1">',
      '#suffix' => '</div>'
    );

    $form['container']['left']['s1']['personal_address']['room_no'] = array(
      '#title' => $this->t('หมายเลขห้อง'),
      '#type' => 'textfield',
      '#prefix' => '<div class="col-6 pl-1 pr-0">',
      '#suffix' => '</div></div>'
    );

    $form['container']['left']['s1']['personal_address']['address2'] = array(
      '#title' => $this->t('หมู่บ้าน / อาคาร'),
      '#type' => 'textfield',
      '#prefix' => '<div class="row">
                      <div class="col-6 pl-0 pr-1">',
      '#suffix' => '</div>'
    );

    $form['container']['left']['s1']['personal_address']['moo'] = array(
      '#title' => $this->t('หมู่ที่'),
      '#type' => 'textfield',
      '#states' => [
        //show this textfield only if the radio 'other' is selected above
        'visible' => [
          ':input[name="select_add_type"]' => ['value' => 1],
        ],
      ],
      '#prefix' => '<div class="col-6 pl-1 pr-0">',
      '#suffix' => '</div></div>'
    );

    $form['container']['left']['s1']['personal_address']['soi'] = array(
      '#title' => $this->t('ตรอก/ซอย'),
      '#type' => 'textfield',
      '#states' => [
        //show this textfield only if the radio 'other' is selected above
        'visible' => [
          ':input[name="select_add_type"]' => ['value' => 1],
        ],
      ],
    );

    $form['container']['left']['s1']['personal_address']['road'] = array(
      '#title' => $this->t('ถนน'),
      '#type' => 'textfield',
      
    );

    $form['container']['left']['s1']['personal_address']['province'] = array(
      '#type' => 'select',
      '#empty_option' => '- เลือก -',
      '#title' => $this->t('จังหวัด *'),
      '#description'  => $this->t(''),
      '#options'      => Utils::getTaxonomy_term('provinces'),
      '#ajax' => [
        'callback' => '::selectChange', // don't forget :: when calling a class method.
        //'callback' => [$this, 'myAjaxCallback'], //alternative notation
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'change',
        'wrapper' => 'personal_address', // This element is updated with this AJAX callback.
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t(''),
        ],
      ],
      '#states' => [
        //show this textfield only if the radio 'other' is selected above
        'visible' => [
          [':input[name="select_add_type"]' => ['value' => 1]],
          'or',
          [':input[name="select_country"]' => ['value' => 'TH']],
        ],
      ],
    );

    $province     = '';
    $district     = '';
    $postal_code  = '';

    $district_options     = array();
    $sub_district_options = array();


    // dpm($values);
    if(!empty($values)){
      $personal_address = $values['container']['left']['s1']['personal_address'];

      $province     = $personal_address['province'];
      $district     = $personal_address['district'];
      $sub_district = $personal_address['sub_district'];

      // dpm($district);
      // dpm($sub_district);

      /////////////////  อำเภอ /////////////////////////////
      if(!empty($province)){
        $filterDistricts = array_filter(Utils::getTaxonomy_term('district'), function ($v) use ($province) {
          return $v['code'] == $province;
        });
  
        foreach ( $filterDistricts as $key => $value) {
          $district_options[$key] = $value['name'];
        } 
      }

      // กรณีมีการเลือก จังหวัด เราต้อง update ตำบล, รหัสไปรษณีย์ ด้วย
      $allowed  = [$district];
      $filtered = array_filter(
        $district_options,
        function ($key) use ($allowed) {
            return in_array($key, $allowed);
        },
        ARRAY_FILTER_USE_KEY
      );

      if(empty($filtered)){
        $sub_district_options = array();
        $district = '';
        $postal_code = '';
      }

     
      /////////////////  อำเภอ /////////////////////////////

      /////////////////  แขวง/ตำบล /////////////////////////////
      if(!empty($district)){
        $filterSubDistricts = array_filter(Utils::getTaxonomy_term('subdistrict'), function ($v) use ($district) {
          return $v['code4'] == $district;
        });

        foreach ( $filterSubDistricts as $key => $value) {
          $sub_district_options[$key] = $value['name'];
        } 
      }
      /////////////////  แขวง/ตำบล /////////////////////////////

      ////////////////  รหัสไปรษณีย์  ///////////////////////////
      if(!empty($sub_district)){
        $postal_codes = Utils::getTaxonomy_term('postal_code');
        $postal_code = $postal_codes[$filterSubDistricts[$sub_district]['ref_pc']];
      }
      ////////////////  รหัสไปรษณีย์  ///////////////////////////
      
    }
    
    $form['container']['left']['s1']['personal_address']['district'] = array(
      '#type' => 'select',
      '#empty_option' => '- เลือก -',
      '#title' => $this->t('อำเภอ *'),
      '#description'  => $this->t(''),
      '#options'      => $district_options,
      '#attributes'=>array('disabled' => empty($province) ? TRUE : FALSE),
      '#ajax' => [
        'callback' => '::selectChange', // don't forget :: when calling a class method.
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'change',
        'wrapper' => 'personal_address', // This element is updated with this AJAX callback.
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t(''),
        ],
      ],
      '#states' => [
        //show this textfield only if the radio 'other' is selected above
        'visible' => [
          [':input[name="select_add_type"]' => ['value' => 1]],
          'or',
          [':input[name="select_country"]' => ['value' => 'TH']],
        ],
      ],
    );

    $form['container']['left']['s1']['personal_address']['sub_district'] = array(
      '#type' => 'select',
      '#empty_option' => '- เลือก -',
      '#title' => $this->t('แขวง/ตำบล *'),
      '#description'  => $this->t(''),
      '#options'      => $sub_district_options,
      '#attributes'=>array('disabled' => empty($district) ? TRUE : FALSE),
      '#ajax' => [
        'callback' => '::selectChange', // don't forget :: when calling a class method.
        'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'change',
        'wrapper' => 'personal_address', // This element is updated with this AJAX callback.
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t(''),
        ],
      ],
      '#states' => [
        //show this textfield only if the radio 'other' is selected above
        'visible' => [
          [':input[name="select_add_type"]' => ['value' => 1]],
          'or',
          [':input[name="select_country"]' => ['value' => 'TH']],
        ],
      ],
    );
   
    $form['container']['left']['s1']['personal_address']['postal_code'] = array(
      '#title' => $this->t('รหัสไปรษณีย์'),
      '#type' => 'textfield',
      '#description'  => $this->t(''),
      '#value' => $postal_code,
      // '#attributes'=>array('disabled' =>TRUE),
      '#attributes'=>array('readonly' => 'readonly'),
      '#states' => [
        //show this textfield only if the radio 'other' is selected above
        'visible' => [
          [':input[name="select_add_type"]' => ['value' => 1]],
          'or',
          [':input[name="select_country"]' => ['value' => 'TH']],
        ],
      ],
    );

    //  ------------ Cambodia ------------

    $is_foreigner = $this->store->get('is_foreigner');
    if($is_foreigner){
      $form['container']['left']['s1']['cambodia'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t(''),
        '#open' => TRUE,
        '#prefix' => '<div id="personal_cambodia">',
        '#suffix' => '</div>'
      );

      $province     = '';
      $district     = '';
      $sub_district = '';
      $postal_code  = '';

      $district_options     = array();
      $sub_district_options = array();

      if(!empty($values)){

        $province     = $form_state->getUserInput()['province_cambodia'];
        $district     = $form_state->getUserInput()['district_cambodia'];
        $sub_district = $form_state->getUserInput()['sub_district_cambodia'];

        // dpm($province);

        /////////////////  อำเภอ /////////////////////////////
        if(!empty($province)){
          $filterDistricts = array_filter(Utils::getTaxonomy_term('districts_cambodia'), function ($v) use ($province) {
            return $v['code'] == $province;
          });
    
          foreach ( $filterDistricts as $key => $value) {
            $district_options[$key] = $value['name'];
          } 
        }

        // กรณีมีการเลือก จังหวัด เราต้อง update ตำบล, รหัสไปรษณีย์ ด้วย
        $allowed  = [$district];
        $filtered = array_filter(
          $district_options,
          function ($key) use ($allowed) {
              return in_array($key, $allowed);
          },
          ARRAY_FILTER_USE_KEY
        );

        if(empty($filtered)){
          $sub_district_options = array();
          $district = '';
          $postal_code = '';
        }
      
        /////////////////  อำเภอ /////////////////////////////

        /////////////////  แขวง/ตำบล /////////////////////////////
        if(!empty($district)){
          $filterSubDistricts = array_filter(Utils::getTaxonomy_term('subdistricts_cambodia'), function ($v) use ($district) {
            return $v['code4'] == $district;
          });

          foreach ( $filterSubDistricts as $key => $value) {
            $sub_district_options[$key] = $value['name'];
          } 
        }
        /////////////////  แขวง/ตำบล /////////////////////////////

        ////////////////  รหัสไปรษณีย์  ///////////////////////////
        if(!empty($sub_district)){
          $postal_codes = Utils::getTaxonomy_term('postalcode_cambodia');
          $postal_code = $postal_codes[$filterSubDistricts[$sub_district]['ref_pc']];
        }
        ////////////////  รหัสไปรษณีย์  ///////////////////////////
      }

      $form['container']['left']['s1']['cambodia']['province_cambodia'] = array(
        '#type' => 'select',
        '#name' => 'province_cambodia',
        '#default_value' => (empty($province)) ? 0 : $province,
        '#empty_option' => '- เลือก -',
        '#title' => $this->t('จังหวัด *'),
        '#description'  => $this->t(''),
        '#options'      => Utils::getTaxonomy_term('provinces_cambodia'),
        '#ajax' => [
          'callback' => '::selectChange', // don't forget :: when calling a class method.
          //'callback' => [$this, 'myAjaxCallback'], //alternative notation
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'personal_cambodia', // This element is updated with this AJAX callback.
          'progress' => [
            'type' => 'throbber',
            'message' => $this->t(''),
          ],
        ],
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'visible' => [
            ':input[name="select_country"]' => ['value' => 'KH'],
          ],
        ],
      );
      
      $form['container']['left']['s1']['cambodia']['district_cambodia'] = array(
        '#type' => 'select',
        '#name' => 'district_cambodia',
        '#default_value' => (empty($district)) ? 0 : $district,
        '#empty_option' => '- เลือก -',
        '#title' => $this->t('อำเภอ *'),
        '#description'  => $this->t(''),
        '#options'      => $district_options,
        '#attributes'=>array('disabled' => empty($province) ? TRUE : FALSE),
        '#ajax' => [
          'callback' => '::selectChange', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'personal_cambodia', // This element is updated with this AJAX callback.
          'progress' => [
            'type' => 'throbber',
            'message' => $this->t(''),
          ],
        ],
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'visible' => [
            ':input[name="select_country"]' => ['value' => 'KH'],
          ],
        ],
      );

      $form['container']['left']['s1']['cambodia']['sub_district_cambodia'] = array(
        '#type' => 'select',
        '#name' => 'sub_district_cambodia',
        '#default_value' => (empty($sub_district)) ? 0 : $sub_district,
        '#empty_option' => '- เลือก -',
        '#title' => $this->t('แขวง/ตำบล *'),
        '#description'  => $this->t(''),
        '#options'      => $sub_district_options,
        '#attributes'=>array('disabled' => empty($district) ? TRUE : FALSE),
        '#ajax' => [
          'callback' => '::selectChange', // don't forget :: when calling a class method.
          'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
          'event' => 'change',
          'wrapper' => 'personal_cambodia', // This element is updated with this AJAX callback.
          'progress' => [
            'type' => 'throbber',
            'message' => $this->t(''),
          ],
        ],
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'visible' => [
            ':input[name="select_country"]' => ['value' => 'KH'],
          ],
        ],
      );
    
      $form['container']['left']['s1']['cambodia']['postal_code_cambodia'] = array(
        '#title' => $this->t('รหัสไปรษณีย์'),
        '#type' => 'textfield',
        '#name' => 'postal_code_cambodia',
        '#description'  => $this->t(''),
        '#value' => $postal_code,
        // '#attributes'=>array('disabled' =>TRUE),
        '#attributes'=>array('readonly' => 'readonly'),
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'visible' => [
            ':input[name="select_country"]' => ['value' => 'KH'],
          ],
        ],
      );

      $form['container']['left']['s1']['personal_address']['city_foreigner'] = array(
        '#title' => $this->t('City/Town'),
        '#type' => 'textfield',
        '#description'  => $this->t(''),
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'invisible' => [
            [
              [':input[name="select_add_type"]' => ['value' => '1']], // Thailand
              'or',
              [':input[name="select_country"]' => ['value' => 'KH']], // Cambodia
            ]
          ],
        ],
      );

      $form['container']['left']['s1']['personal_address']['province_foreigner'] = array(
        '#title' => $this->t('Province/State/Departmant'),
        '#type' => 'textfield',
        '#description'  => $this->t(''),
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'invisible' => [
            [
              [':input[name="select_add_type"]' => ['value' => '1']], // Thailand
              'or',
              [':input[name="select_country"]' => ['value' => 'KH']], // Cambodia
            ]
          ],
        ],
      );

      $form['container']['left']['s1']['personal_address']['postal_code_foreigner'] = array(
        '#title' => $this->t('Postal code'),
        '#type' => 'textfield',
        '#description'  => $this->t(''),
        '#value' => $postal_code,
        '#states' => [
          //show this textfield only if the radio 'other' is selected above
          'invisible' => [
            [
              [':input[name="select_add_type"]' => ['value' => '1']], // Thailand
              'or',
              [':input[name="select_country"]' => ['value' => 'KH']], // Cambodia
            ]
          ],
        ],
      );
    }

    $form['container']['left']['s1']['condition'] = array(
      '#type' => 'fieldset',
      '#open' => TRUE,
    );

    $form['container']['left']['s1']['condition']['language'] = array(
      '#type'   => 'select',
      '#empty_option' => '- เลือก -',
      '#title'  => $this->t('ภาษาที่ใช้ในการสื่อสาร *'),
      '#description'  => $this->t(''),
      '#options'=> Utils::getTaxonomy_Term('language'),
    );

    $terms_and_conditions = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(82)->get('field_content')->getValue()[0]['value'];
    // dpm($terms_and_conditions);

    $form['container']['left']['s1']['condition']['agree'] = array(
      '#type' =>'checkbox',
      '#title'=>t('ยอมรับข้อกำหนดและเงื่อนไขการเป็นสมาชิกบิ๊กการ์ด *'),
      '#description'  => $this->t(''),
      '#prefix' => '',
      '#suffix' => '<div id="condition-agree-message">' . $terms_and_conditions . '</div>',
    );

    $form['container']['left']['s1']['submit'] = array(
      '#type' => 'submit',
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

    $form['container']['left']['s1']['previous'] = array(
      '#type' => 'link',
      '#title' => $this->t('<< ย้อนกลับ'),
      // '#attributes' => array(
      //   'class' => array('button'),
      // ),
      '#url' => Url::fromRoute('new_member.step1'),
      // '#url' => Url::fromRoute('new_member.step1')->toString().'/'.$type,
    );

    $form['container']['right'] = array(
      '#type' => 'container',
      '#prefix' => '<div id="container-right" class="col-lg-6 col-md-6 col-sm-12 col-12">',
      '#suffix' => '</div>',
    );

    $form['container']['right']['text'] = array(
      '#type' => 'item',
      '#prefix' => Utils::getTextRight(),
      '#suffix' => '</div></div>',
    );

    return $form;
  }

  public function selectChange(array &$form, FormStateInterface $form_state) {
    // $bro_from = $form['bro_from']['#value']; 
    // $bro_to = $form['bro_to']['#value']; 
    // dpm($bro_from);
    // dpm($bro_to);

    $btn_name   = $form_state->getTriggeringElement()['#name'];

    switch($btn_name){
      case 'province_cambodia':
      case 'district_cambodia':
      case 'sub_district_cambodia':{
        $form_state->setRebuild();
        return  $form['container']['left']['s1']['cambodia'];
      break;
      }

      default:{
        $province = $form_state->getUserInput()['container']['left']['s1']['personal_address']['province'];

        $form_state->set('province', $province);
        $form_state->setRebuild();
      
        return $form['container']['left']['s1']['personal_address'];
      break;
      }
    }

    return  $form;
  }

  public function submitAjax(array &$form, FormStateInterface $form_state) {
    // drupal_set_message("s1confirm_form_submit.");
    $btn_name   = $form_state->getTriggeringElement()['#name'];

    $condition_agree=  $form_state->getUserInput()['container']['left']['s1']['condition']['agree'];
    if(!empty($condition_agree)){

      $form_state->setRebuild();
    }

    // dpm($form_state->getUserInput()['container']['personal_information']['name']);
  }

  
  public function callbackAjax(array &$form, FormStateInterface $form_state) {
    $btn_name   = $form_state->getTriggeringElement()['#name'];
    $ajax_response = new AjaxResponse();
    $type = $this->store->get('type');
    $pass = TRUE;

    $personal_information=  $form_state->getUserInput()['container']['left']['s1']['personal_information'];

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

    // reset all field
    $selector_input = '#edit-container-left-branch-register';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-branch-register--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

    $selector_input = '#edit-container-left-s1-personal-information-laser';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-laser--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

    $selector_input = '#edit-container-left-s1-personal-information-welfare-id';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-welfare-id--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

    $selector_input = '#edit-container-left-s1-personal-information-name';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-name--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

    $selector_input = '#edit-container-left-s1-personal-information-last-name';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-last-name--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_description = '#edit-container-left-s1-personal-information-gender--wrapper--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-information-bd-date-of-birth';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-bd-date-of-birth--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));     
    
    $selector_input = '#edit-container-left-s1-personal-information-bd-month-of-birth';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-bd-month-of-birth--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-information-bd-year-of-birth';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-bd-year-of-birth--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-information-email';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-information-email--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-address-address1';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-personal-address-address1--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-address-province';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-personal-address-province--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-address-district';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-personal-address-district--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-address-sub-district';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-personal-address-sub-district--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_input = '#edit-container-left-s1-personal-address-postal-code';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-personal-address-postal-code--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, '')); 
    
    // --------------------------- Cambodia
    $selector_input = '#edit-container-left-s1-cambodia-province-cambodia';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-cambodia-province-cambodia--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, '')); 

    $selector_input = '#edit-container-left-s1-cambodia-district-cambodia';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-cambodia-district-cambodia--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, '')); 

    $selector_input = '#edit-container-left-s1-cambodia-sub-district-cambodia';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-cambodia-sub-district-cambodia--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, '')); 

    $selector_input = '#edit-container-left-s1-cambodia-postal-code-cambodia';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear_disable));
    $selector_description = '#edit-container-left-s1-cambodia-postal-code-cambodia--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, '')); 
    
    // --------------------------- 

    $selector_input = '#edit-container-left-s1-condition-language';
    $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
    $selector_description = '#edit-container-left-s1-condition-language--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    $selector_description = '#edit-container-left-s1-condition-agree--description';
    $ajax_response->addCommand(new HtmlCommand($selector_description, ''));      

    // -------------------------------------------


    if($type == 'normal')
    { 
      // สาขาที่สมัคร
      $store_code = $form_state->getUserInput()['container']['left']['branch_register'];
      if(!empty($store_code)){
        $data_validate_store = Utils::validate_store_api($store_code);
        $this->store->set('data_validate_store', $data_validate_store);
        if($data_validate_store['is_valid'] == 'N'){
          $pass = FALSE;
          $selector_input = '#edit-container-left-branch-register';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-branch-register--description';
          $text_description = $this->t('กรุณาใส่ตัวเลขที่ถูกต้องในฟิลด์นี้');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
      }

      \Drupal::logger('nook')->notice('D'.$pass?'x':'y');

      // รหัสหลังบัตรประชาชน
      $laser = $personal_information['laser'];
      if(!empty($laser)){
        $id_card = $personal_information['id_card'];
        $name = $personal_information['name'];
        $last_name = $personal_information['last_name'];
        $date_of_birth = $personal_information['bd']['date_of_birth'];
        $month_of_birth = $personal_information['bd']['month_of_birth'];
        $year_of_birth = $personal_information['bd']['year_of_birth'];
        $birth_date = ($year_of_birth+543) . str_pad($month_of_birth, 2, "0", STR_PAD_LEFT) . str_pad($date_of_birth, 2, "0", STR_PAD_LEFT);
        $data_check_card = Utils::check_card_api($id_card, $name, $last_name, $birth_date, $laser);
        $this->store->set('data_check_card', $data_check_card);
        
        // 0 สถานะปกติ
        // 1,2,3,4,5,9 สถานะไม่ปกติ
        if(in_array($data_check_code['return_code'], ['1', '2', '3', '4', '5'])){ 
          $pass = FALSE;
          $selector_input = '#edit-container-left-s1-personal-information-laser';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-information-laser--description';
          // $text_description = 'บัตรสถานะไม่ปกติ';
          $text_description = $this->t('โปรดกรอกข้อมูลให้ตรงตามหน้าบัตรประชาชนล่าสุด');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }else if($data_check_code['return_code'] == '9'){
          $pass = FALSE;
          $selector_input = '#edit-container-left-s1-personal-information-laser';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-information-laser--description';
          // $text_description = 'บัตรสถานะไม่ปกติ';
          $text_description = $this->t('ขออภัย ระบบขัดข้อง โปรดลงทะเบียนใหม่ภายหลัง');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
      }

      // บัตรสวัสดิการแห่งรัฐ
      $welfare_id = $personal_information['welfare_id'];
      if(!empty($welfare_id)){
        $data_check_exists_welfare_id = Utils::check_exists_welfare_id_api($welfare_id);
        $this->store->set('data_check_exists_welfare_id', $data_check_exists_welfare_id);

        if($data_check_exists_welfare_id['is_exists'] == 'N'){
          $pass = FALSE;
          $selector_input = '#edit-container-left-s1-personal-information-welfare-id';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-information-welfare-id--description';
          $text_description = $this->t('บัตรสวัสดิการแห่งรัฐไม่ถูกต้อง');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
      }
    }// end type

    // ชื่อ
    $name = $personal_information['name'];
    if(empty($name)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-personal-information-name';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

      $selector_description = '#edit-container-left-s1-personal-information-name--description';
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }

    // นามสกุล
    $last_name = $personal_information['last_name'];
    if(empty($last_name)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-personal-information-last-name';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

      $selector_description = '#edit-container-left-s1-personal-information-last-name--description';
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }

    // เพศ
    $gender = $personal_information['gender'];
    if(empty($gender)){
      $pass = FALSE;
      $text_description = $this->t('กรุณาเลือกตัวเลือกใดตัวเลือกหนึ่ง');
      $selector_description = '#edit-container-left-s1-personal-information-gender--wrapper--description';
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }
    \Drupal::logger('nook')->notice('D'.$pass?'x':'y');
    if($type == 'junior')
    {
      $occupation = $personal_information['occupation'];
      if(empty($occupation)){
        $pass = FALSE;
        $text_description = $this->t('กรุณาเลือกตัวเลือกใดตัวเลือกหนึ่ง');
        $selector_description = '#edit-container-left-s1-personal-information-occupation';
        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
      }
    }

    // วันเกิด
    $bd_date = $personal_information['bd']['date_of_birth'];
    $bd_month = $personal_information['bd']['month_of_birth'];
    $bd_year = $personal_information['bd']['year_of_birth'];

    if(empty($bd_date)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-personal-information-bd-date-of-birth';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

      $selector_description = '#edit-container-left-s1-personal-information-bd-date-of-birth--description';
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }
    \Drupal::logger('nook')->notice('A'.$pass?'x':'y');
    if(empty($bd_month)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-personal-information-bd-month-of-birth';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

      $selector_description = '#edit-container-left-s1-personal-information-bd-month-of-birth--description';
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }

    if(empty($bd_year)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-personal-information-bd-year-of-birth';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

      $selector_description = '#edit-container-left-s1-personal-information-bd-year-of-birth--description';
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }
    \Drupal::logger('nook')->notice('C'.$pass?'x':'y');
    $email=  $personal_information['email'];
    if(empty($email)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-personal-information-email';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      // กรุณาใส่อีเมล์ที่ถูกต้อง ตัวอย่างเช่น johndoe@domain.com.
      $selector_description = '#edit-container-left-s1-personal-information-email--description';
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }
    if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-personal-information-email';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
      $text_description = $this->t('กรุณาใส่อีเมล์ที่ถูกต้อง ตัวอย่างเช่น johndoe@domain.com');
      $selector_description = '#edit-container-left-s1-personal-information-email--description';
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }

    $is_foreigner = $this->store->get('is_foreigner');

    $add_type = $form_state->getUserInput()['select_add_type'];
    $this->store->set('add_type', $form_state->getUserInput()['select_add_type']);

    $country = $form_state->getUserInput()['select_country'];
    $this->store->set('country', $form_state->getUserInput()['select_country']);

    $personal_address =  $form_state->getUserInput()['container']['left']['s1']['personal_address'];
    \Drupal::logger('nook')->notice($is_foreigner.'dfdfdfD'.($pass?'x':'y'));
    if($is_foreigner){
      // Passport + YN + nationality 999
      if($add_type != '1'){
        // validate Country
        if(empty($country)){
          $pass = FALSE;

          $selector_input = '#select-country';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-address-country-foreigner--description';
          $text_description = $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
      }

      if($add_type == '1' || $country == 'TH'){
        
        // Thailand
        $address1 = $personal_address['address1'];
        if(empty($address1)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-personal-address-address1';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-address-address1--description';
          $text_description = $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

        // จังหวัด
        $province = $personal_address['province'];
        if(empty($province)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-personal-address-province';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-address-province--description';
          $text_description = $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
        
        // อำเภอ
        $district = $personal_address['district'];
        if(empty($district)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-personal-address-district';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-address-district--description';
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

        // แขวง/ตำบล  
        $sub_district = $personal_address['sub_district'];
        if(empty($sub_district)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-personal-address-sub-district';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-address-sub-district--description';
          $text_description = $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

        // รหัสไปรษณีย์ 
        $postal_code = $personal_address['postal_code'];
        if(empty($postal_code)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-personal-address-postal-code';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-personal-address-postal-code--description';
          $text_description = $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

      }else if($country == 'KH'){
        $province_cambodia = $form_state->getUserInput()['province_cambodia'];
        $district_cambodia = $form_state->getUserInput()['district_cambodia'];
        $sub_district_cambodia = $form_state->getUserInput()['sub_district_cambodia'];
        // Cambodia

        // จังหวัด
        if(empty($province_cambodia)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-cambodia-province-cambodia';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-cambodia-province-cambodia--description';
          $text_description = $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }
        
        // อำเภอ
        if(empty($district_cambodia)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-cambodia-district-cambodia';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-cambodia-district-cambodia--description';
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

        // แขวง/ตำบล  
        if(empty($sub_district_cambodia)){
          $pass = FALSE;

          $selector_input = '#edit-container-left-s1-cambodia-sub-district-cambodia';
          $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

          $selector_description = '#edit-container-left-s1-cambodia-sub-district-cambodia--description';
          $text_description = $this->t('ข้อมูลนี้จำเป็น');
          $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
          $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        }

        // รหัสไปรษณีย์ 
        // if(empty($postal_code_cambodia)){
        //   $pass = FALSE;

        //   $selector_input = '#edit-container-left-s1-personal-address-postal-code';
        //   $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

        //   $selector_description = '#edit-container-left-s1-personal-address-postal-code--description';
        //   $text_description = $this->t('ข้อมูลนี้จำเป็น');
        //   $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
        //   $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
        // }
      }else{
          // Others
        $city_foreigner = $personal_address['city_foreigner'];
        $province_foreigner = $personal_address['province_foreigner'];
        $postal_code_foreigner = $personal_address['postal_code_foreigner'];
      }

    }else{
      // ID Card + NN / ID card + YN / Passport + YN + nationality 1

      // บ้านเลขที่
      $personal_address =  $form_state->getUserInput()['container']['left']['s1']['personal_address'];
      $address1 = $personal_address['address1'];
      if(empty($address1)){
        $pass = FALSE;

        $selector_input = '#edit-container-left-s1-personal-address-address1';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

        $selector_description = '#edit-container-left-s1-personal-address-address1--description';
        $text_description = $this->t('ข้อมูลนี้จำเป็น');
        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
      }

      // จังหวัด
      $province = $personal_address['province'];
      if(empty($province)){
        $pass = FALSE;

        $selector_input = '#edit-container-left-s1-personal-address-province';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

        $selector_description = '#edit-container-left-s1-personal-address-province--description';
        $text_description = $this->t('ข้อมูลนี้จำเป็น');
        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
      }
      
      // อำเภอ
      $district = $personal_address['district'];
      if(empty($district)){
        $pass = FALSE;

        $selector_input = '#edit-container-left-s1-personal-address-district';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

        $selector_description = '#edit-container-left-s1-personal-address-district--description';
        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
      }

      // แขวง/ตำบล  
      $sub_district = $personal_address['sub_district'];
      if(empty($sub_district)){
        $pass = FALSE;

        $selector_input = '#edit-container-left-s1-personal-address-sub-district';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

        $selector_description = '#edit-container-left-s1-personal-address-sub-district--description';
        $text_description = $this->t('ข้อมูลนี้จำเป็น');
        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
      }
      \Drupal::logger('nook')->notice('B'.$pass?'x':'y');
      // รหัสไปรษณีย์ 
      $postal_code = $personal_address['postal_code'];
      if(empty($postal_code)){
        $pass = FALSE;

        $selector_input = '#edit-container-left-s1-personal-address-postal-code';
        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

        $selector_description = '#edit-container-left-s1-personal-address-postal-code--description';
        $text_description = $this->t('ข้อมูลนี้จำเป็น');
        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
      }

    }
    \Drupal::logger('nook')->notice('A'.$pass?'x':'y');
    // ภาษาที่ใช้ในการสื่อสาร 
    $condition=  $form_state->getUserInput()['container']['left']['s1']['condition'];
    $language = $condition['language'];
    if(empty($language)){
      $pass = FALSE;

      $selector_input = '#edit-container-left-s1-condition-language';
      $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

      $selector_description = '#edit-container-left-s1-condition-language--description';
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));      
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }

    // Agreement
    $agree=  $condition['agree'];
    if(empty($agree)){
      $pass = FALSE;

      $selector_description = '#edit-container-left-s1-condition-agree--description';
      $text_description = $this->t('ข้อมูลนี้จำเป็น');
      $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
      $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
    }
    
    
    // dpm($pass);
    \Drupal::logger('nook')->notice($pass?'x':'y');
    if($pass){
       dpm('pass naja');
      // return $form['container'];
      $this->prepare_register_info($form,$form_state);

      $url = Url::fromRoute('new_member.step3');
      $command = new RedirectCommand($url->toString());
      $ajax_response->addCommand($command);
    }
    return $ajax_response;
  }

  public function prepare_register_info(array &$form, FormStateInterface $form_state) {
    // dpm('in prepare');
    $type = $this->store->get('type');
    $personal_information = $form_state->getUserInput()['container']['left']['s1']['personal_information'];
    $personal_address = $form_state->getUserInput()['container']['left']['s1']['personal_address'];
    $condition = $form_state->getUserInput()['container']['left']['s1']['condition'];
    
    if($type == 'normal')
    {
      $this->store->set('issued_store', $personal_information['branch_register']);
      $this->store->set('welfare_id', $personal_information['welfare_id']);
    }

    if($type == 'junior')
    {
     $occupation = Utils::getTaxonomy_Term('occupation')[$personal_information['occupation']];
     $this->store->set('occupation', $occupation);
    }else
    {
      $this->store->set('occupation', $personal_information['occupation']);
    }

    \Drupal::logger('nook')->notice('after occu');
    //$this->store->set('issued_store', $personal_information['branch_register']);
    //$this->store->set('welfare_id', $personal_information['welfare_id']);
    $this->store->set('title', $personal_information['prefix_name']);
    $this->store->set('name', $personal_information['name']);
    $this->store->set('last_name', $personal_information['last_name']);
    $this->store->set('gender', $personal_information['gender']);
    //$this->store->set('occupation', $personal_information['occupation']);

    $date_of_birth = $personal_information['bd']['date_of_birth'];
    $month_of_birth = $personal_information['bd']['month_of_birth'];
    $year_of_birth = $personal_information['bd']['year_of_birth'];
    $birth_date = $year_of_birth . '-' . str_pad($month_of_birth, 2, "0", STR_PAD_LEFT) . '-' . str_pad($date_of_birth, 2, "0", STR_PAD_LEFT);
    $this->store->set('birth_date', $birth_date);
    $this->store->set('email', $personal_information['email']);

    $this->store->set('home_phone', $personal_information['tel_home']['home_phone']);
    $this->store->set('home_phone_ext', $personal_information['tel_home']['ext']);

    $this->store->set('address1', $personal_address['address1']);
    $this->store->set('room_no', $personal_address['room_no']);
    $this->store->set('address2', $personal_address['address2']);
    $this->store->set('moo', $personal_address['moo']);
    $this->store->set('soi', $personal_address['soi']);
    $this->store->set('road', $personal_address['road']);

    $add_type = $form_state->getUserInput()['select_add_type'];
    $this->store->set('add_type', $form_state->getUserInput()['select_add_type']);
    
    if($type == 'junior' || $type == 'gpf')
    {
      $country = 'TH';
    }else
    {
      $country = $form_state->getUserInput()['select_country'];
    }
    $this->store->set('country', $form_state->getUserInput()['select_country']);

    if($add_type == '1' || $country == 'TH'){
      // 1 Thailand
      $this->store->set('province', $personal_address['province']);
      $this->store->set('district', $personal_address['district']);
      $this->store->set('sub_district', $personal_address['sub_district']);
      $this->store->set('postal_code', $personal_address['postal_code']);
    }else{
      // 5 Overseas
      if($country == 'KH'){ // Cambodia
        $this->store->set('province', $form_state->getUserInput()['province_cambodia']);
        $this->store->set('district', $form_state->getUserInput()['district_cambodia']);
        $this->store->set('sub_district', $form_state->getUserInput()['sub_district_cambodia']);
        $this->store->set('postal_code', $form_state->getUserInput()['postal_code_cambodia']);
      }else{ // Others
        $this->store->set('city_foreigner', $personal_address['city_foreigner']);
        $this->store->set('province_foreigner', $personal_address['province_foreigner']);
        $this->store->set('postal_code_foreigner', $personal_address['postal_code_foreigner']);
      }
    }
  
    $this->store->set('language', $condition['language']);
    $this->store->set('agree', $condition['agree']);

    // ----- foreigner field -----
   
    $form_state->setRebuild();

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $this->store->set('age', $form_state->getValue('age'));
    // $this->store->set('location', $form_state->getValue('location'));

    // // Save the data
    // parent::saveData();
    // $form_state->setRedirect('new_member.step3');
  }
}
