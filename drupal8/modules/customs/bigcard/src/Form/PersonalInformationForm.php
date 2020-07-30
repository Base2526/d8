<?php

/**
 * @file
 * Contains \Drupal\demo\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\bigcard\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\bigcard\Utils\Utils;

class PersonalInformationForm extends FormBase {
    /**
     * {@inheritdoc}.
     */

    // private $data_personal_info;
    // private $data_address_info;

     /**
     * Class constructor.
     */
    public function __construct() {
        $this->language = \Drupal::languageManager()->getCurrentLanguage()->getId();

        $global_config = \Drupal\config_pages\Entity\ConfigPages::config('global_config');
        if(isset( $global_config )){
            $this->is_debug =  $global_config->get('field_is_debug')->value;
        }

        // $this->data_personal_info = Utils::personal_info_api();

        // $this->data_address_info = Utils::address_info_api();
        // dpm($this->data_personal_info);
        // dpm($_SESSION['auth_token']);

        // dpm('__construct > ' .  rand() );
    }

    public function getFormId() {
        return 'personal_information_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $time_start = microtime(true); 

        $data   = $form_state->get('data');

        $values = $form_state->getValues();

        // $data_personal_info = $this->data_personal_info;
        // dpm($data_personal_info);

        ////
        $data_personal_info   = $form_state->get('data_personal_info');
        if(empty($data_personal_info)){
            $data_personal_info = Utils::personal_info_api();

            $form_state->set('data_personal_info', $data_personal_info );
        }

        $data_address_info   = $form_state->get('data_address_info');
        if(empty($data_address_info)){
            $data_address_info = Utils::address_info_api();

            $form_state->set('data_address_info', $data_address_info );
        }

        $options_prefix_name  = $form_state->get('options_prefix_name');
        if(empty($options_prefix_name)){
            $options_prefix_name = Utils::getTaxonomy_Term('prefix_name');

            $form_state->set('options_prefix_name', $options_prefix_name );
        }

        $options_sex = $form_state->get('options_sex');
        if(empty($options_sex)){
            $options_sex = Utils::getTaxonomy_Term('sex');

            $form_state->set('options_sex', $options_sex );
        }

        $options_dates = $form_state->get('options_dates');
        if(empty($options_dates)){
            $options_dates = Utils::getTaxonomy_Term('dates');

            $form_state->set('options_dates', $options_dates );
        }

        $options_month = $form_state->get('options_month');
        if(empty($options_month)){
            $options_month = Utils::getTaxonomy_Term('month');

            $form_state->set('options_month', $options_month );
        }

        $options_year = $form_state->get('options_year');
        if(empty($options_year)){
            $options_year = Utils::getTaxonomy_Term('year');

            $form_state->set('options_year', $options_year );
        }
        
        $options_provinces = $form_state->get('options_provinces');
        if(empty($options_provinces)){
            $options_provinces = Utils::getTaxonomy_Term('provinces');

            $form_state->set('options_provinces', $options_provinces );
        }

        $options_district = $form_state->get('options_district');
        if(empty($options_district)){
            $options_district = Utils::getTaxonomy_Term('district');

            $form_state->set('options_district', $options_district );
        }

        $options_subdistrict = $form_state->get('options_subdistrict');
        if(empty($options_subdistrict)){
            $options_subdistrict = Utils::getTaxonomy_Term('subdistrict');

            $form_state->set('options_subdistrict', $options_subdistrict );
        }

        // Utils::getTaxonomy_term('postal_code')
        $options_postal_codes = $form_state->get('options_postal_codes');
        if(empty($options_postal_codes)){
            $options_postal_codes = Utils::getTaxonomy_Term('postal_code');

            $form_state->set('options_postal_codes', $options_postal_codes );
        }

        $options_language = $form_state->get('options_language');
        if(empty($options_language)){
            $options_language = Utils::getTaxonomy_Term('language');

            $form_state->set('options_language', $options_language );
        }
    
        $options_news = $form_state->get('options_news');
        if(empty($options_news)){
            $options_news = Utils::getTaxonomy_Term('news');

            $form_state->set('options_news', $options_news );
        }
        ////


        // dpm($this->language);
        if($this->language == 'th'){
            $data_personal_info['name'] = $data_personal_info['t_name'];
            $data_personal_info['last_name'] = $data_personal_info['t_last_name'];
        }else if($this->language == 'en'){
            $data_personal_info['name'] = $data_personal_info['e_name'];
            $data_personal_info['last_name'] = $data_personal_info['e_last_name'];
        }else if($this->language == 'cn'){
            $data_personal_info['name'] = $data_personal_info['e_name'];
            $data_personal_info['last_name'] = $data_personal_info['e_last_name'];
        }

        // dpm($data_personal_info);

        $birth_date_arr = explode('-', $data_personal_info['birth_date']);
        $data_personal_info['year_of_birth'] = (int)$birth_date_arr[0];
        $data_personal_info['month_of_birth'] = (int)$birth_date_arr[1];
        $data_personal_info['date_of_birth'] = (int)$birth_date_arr[2];

        // $data_address_info = Utils::address_info_api();
        // dpm($data_address_info);


        $form['#tree'] = TRUE;
        $form['container'] = array(
            '#type' => 'container',
        );

        $form['container']['id_card'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('เลขที่บัตรประชาชน'),
            '#default_value'=> $data_personal_info['id_card'],
            '#attributes'=>array('readonly' => 'readonly'),
            '#prefix' => '<div class="row">
                            <div class="col-12 py-2 fs-xl fw-400">
                                <i class="fab fa-product-hunt fs-md fw-600"></i>  ' . $this->t('ข้อมูลส่วนตัว') . '
                            </div>
                            <div class="line-bottom"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 py-3">
                                <div id="personal-info-form">',
            '#suffix' => '',
        );

        $form['container']['tel'] = array(
            '#type' => 'textfield',
            '#default_value'=> $data_personal_info['mobile_phone'],
            '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
            '#attributes'=>array('readonly' => 'readonly'),
        );

        // $form['container']['forgot_password'] = array(
        //     '#type' => 'link',
        //     '#title' => $this->t('เปลี่ยนรหัสผ่าน'),
        //     '#attributes' => array(
        //       'class' => array('button'),
        //     ),
        //     '#url' => Url::fromRoute('new_member.step1'),
        // );

        $form['container']['forgot_password'] = array(
            '#type' => 'submit',
            '#name' => 'forgot_password',
            '#value' => $this->t('เปลี่ยนรหัสผ่าน'),
            '#submit' => ['::submitAjax'],
            '#ajax' => [
              'callback' => '::callbackAjax',
              'wrapper' => 'container_forgot_password',
              'progress' => array(
                  // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
                  'type' => 'throbber',
                  // Message to show along progress graphic. Default: 'Please wait...'.
                  'message' => NULL,
              ),
            ],
        );

        $form['container']['forgot_password']['s1'] = array(
            '#type' => 'container',
            '#prefix' => '<div id="container_forgot_password">',
            '#suffix' => '</div>',
        );

        if(!empty($data['forgot_password'])){
            // รหัสผ่านเดิม
            $form['container']['forgot_password']['s1']['old_password'] = array(
                '#type' => 'password',
                '#title' => $this->t('รหัสผ่าน'),
                // '#default_value' => $this->store->get('tel'),
                // '#description'=>$this->t(''),
                '#prefix' => '',
                '#suffix' => '<small id="old-password-description"></small>',
            );
        
            // รหัสผ่านใหม่
            $form['container']['forgot_password']['s1']['new_password'] = array(
                '#type' => 'password',
                '#title' => $this->t('รหัสผ่านใหม่'),
                // '#default_value' => $this->store->get('tel'),
                // '#description'=>$this->t(''),
                '#prefix' => '',
                '#suffix' => '<small id="new-password-description"></small>',
            );
        
            $form['container']['forgot_password']['s1']['confirm_new_password'] = array(
                '#type' => 'password',
                '#title' => $this->t('ยืนยันรหัสผ่านใหม่'),
                // '#default_value' => $this->store->get('tel'),
                // '#description'=>$this->t(''),
                '#prefix' => '',
                '#suffix' => '<small id="confirm-new-password-description"></small>',
            );
        }

        $form['container']['welfare_id'] = array(
            '#type' => 'textfield',
            '#default_value'=> $data_personal_info['welfare_id'],
            '#title' => $this->t('บัตรสวัสดิการแห่งรัฐ'),
            '#attributes'=>array('readonly' => 'readonly'),
        );

        // $prefix_name_tid = $form_state->getUserInput()['select_prefix_name'];
        // $prefix_name_rid = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($prefix_name_tid)->get('field_refer_id')->getValue()[0]['value'];

        $form['container']['prefix_name'] = array(
            '#type'         => 'select',
            '#title'        => $this->t('คำนำหน้า'),
            '#attributes' => [
                'id' => 'select-prefix-name',
                'name' => 'select_prefix_name',
            ],
            '#default_value'=> $data_personal_info['title'],
            '#options'      => $options_prefix_name, //Utils::getTaxonomy_Term('prefix_name'),
        );

        $form['container']['name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('ชื่อ'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['name'],
            '#prefix' => '<div class="row">
                            <div class="col-6 pl-0 pr-1">',
            '#suffix' => '</div>'
        );
    
        $form['container']['last_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('นามสกุล'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['last_name'],
            '#prefix' => '<div class="col-6 pl-1 pr-0">',
            '#suffix' => '</div></div>'
        );

        $form['container']['gender'] = array(
            '#type'         => 'select',
            '#title'        => $this->t('เพศ'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['gender'],
            '#options'      => $options_sex, //Utils::getTaxonomy_Term('sex'),
            '#attributes' => [
                'id' => 'select-gender',
                'name' => 'select_gender',
            ],
        );

        $form['container']['occupation'] = array(
            '#type' => 'textfield',
            '#default_value'=> $data_personal_info['occupation'],
            '#title' => $this->t('อาชีพ'),
        );

        $form['container']['bd'] = array(
            '#type' => 'container',
        );
        
        $form['container']['bd']['date_of_birth'] = array(
            '#type' => 'select',
            // '#options'      => ['1', '2', '3', '4', '5', '6'],
            '#options'      => $options_dates, ///Utils::getTaxonomy_Term('dates'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['date_of_birth'],
            '#attributes' => [
                'id' => 'select-date-of-birth',
                'name' => 'select_date_of_birth',
            ],
            '#prefix' => '<div id="" class="row">
                                <div class="col-12">
                                    ' . $this->t('วันเกิด') . '
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 pl-0 pr-1">',
            '#suffix' => '</div>',
        );
    
        $form['container']['bd']['month_of_birth'] = array(
            '#type' => 'select',
            '#options'      =>  $options_month, //Utils::getTaxonomy_Term('month'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['month_of_birth'],
            '#attributes' => [
                'id' => 'select-month-of-birth',
                'name' => 'select_month_of_birth',
            ],
            '#prefix' => '<div class="col-4 pl-1 pr-1">',
            '#suffix' => '</div>',
        );
    
        $form['container']['bd']['year_of_birth'] = array(
            '#type' => 'select',
            '#options'      => $options_year, // Utils::getTaxonomy_Term('year'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['year_of_birth'],
            '#attributes' => [
                'id' => 'select-year-of-birth',
                'name' => 'select_year_of_birth',
            ],
            '#prefix' => '<div class="col-4 pl-1 pr-0">',
            '#suffix' => '</div></div>',
        );

        $form['container']['email'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('อีเมลล์'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['email'],
        );

        $form['container']['tel_home']['home_phone'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('เบอร์โทรศัพท์บ้าน'),
            '#default_value'=> $data_address_info['home_phone'],
            '#prefix' => '<div class="row">
                        <div class="col-8 pl-0 pr-1">',
            '#suffix' => '</div>',
        );

        $form['container']['tel_home']['txt'] = array(
            '#type' => 'item',
            '#markup' => $this->t('ต่อ'),
            '#prefix' => '<div class="col-1 pl-1 pr-1 pull-bottom-tel">',
            '#suffix' => '</div>',
        );
    
        $form['container']['tel_home']['home_phone_ext'] = array(
            '#type' => 'textfield',
            '#default_value'=> $data_address_info['home_phone_ext'],
            '#prefix' => '<div class="col-3 pl-1 pr-0 pull-bottom">',
            '#suffix' => '</div></div>',
        );


        $form['container']['address'] = array(
            '#type' => 'markup',
            '#markup' => $this->t('ที่อยู่ที่ติดต่อได้'),
            '#prefix' => '',
            '#suffix' => ''
        );

        $form['container']['address1'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('บ้านเลขที่'),
            '#description' => t(''),
            '#default_value'=> $data_address_info['address1'],
            '#prefix' => '<div class="row">
                            <div class="col-6 pl-0 pr-1">',
            '#suffix' => '</div>'
        );
    
        $form['container']['room_no'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('หมายเลขห้อง'),
            '#default_value'=> $data_address_info['room_no'],
            '#prefix' => '<div class="col-6 pl-1 pr-0">',
            '#suffix' => '</div></div>'
        );

        $form['container']['address2'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('หมู่บ้าน / อาคาร'),
            '#default_value'=> $data_address_info['address2'],
            '#prefix' => '<div class="row">
                            <div class="col-6 pl-0 pr-1">',
            '#suffix' => '</div>'
        );
    
        $form['container']['moo'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('หมู่ที่'),
            '#default_value'=> $data_address_info['moo'],
            '#prefix' => '<div class="col-6 pl-1 pr-0">',
            '#suffix' => '</div></div>'
        );

        $form['container']['soi'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('ตรอก/ซอย'),
            '#default_value'=> $data_address_info['soi'],
        );

        $form['container']['road'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('ถนน'),
            '#default_value'=> $data_address_info['road'],

        );

        $form['container']['s1'] = array(
            '#type' => 'container',
            '#prefix' => '<div id="container_s1">',
            '#suffix' => '</div>'
        );
        
        $province     = ( $data_address_info['province'] == 999  ) ? 0 : $data_address_info['province'] ;
        $district     = ( $data_address_info['district'] == 9999 ) ? 0 : $data_address_info['district'];
        $sub_district = ( $data_address_info['sub_district'] == 9999 ) ? 0 : $data_address_info['sub_district'];
        $postal_code  = '';

        $district_options     = array();
        $sub_district_options = array();

        
        if(!empty($values)){
            $personal_address = $values['container']['s1'];

            $province     = $personal_address['province'];
            $district     = $personal_address['district'];
            $sub_district = $personal_address['sub_district'];

            // dpm($district);
            // dpm($sub_district);

            /////////////////  อำเภอ /////////////////////////////
            if(!empty($province)){
                $filterDistricts = array_filter( $options_district /*Utils::getTaxonomy_term('district')*/ , function ($v) use ($province) {
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
                $filterSubDistricts = array_filter( $options_subdistrict /*Utils::getTaxonomy_term('subdistrict')*/ , function ($v) use ($district) {
                    return $v['code4'] == $district;
                });

                foreach ( $filterSubDistricts as $key => $value) {
                    $sub_district_options[$key] = $value['name'];
                } 
            }
            /////////////////  แขวง/ตำบล /////////////////////////////

            ////////////////  รหัสไปรษณีย์  ///////////////////////////
            if(!empty($sub_district)){
                $postal_codes = $options_postal_codes;//Utils::getTaxonomy_term('postal_code');
                $postal_code = $postal_codes[$filterSubDistricts[$sub_district]['ref_pc']];
            }
            ////////////////  รหัสไปรษณีย์  ///////////////////////////
        }else{
            
            /////////////////  อำเภอ /////////////////////////////
            if(!empty($province)){
                $filterDistricts = array_filter( $options_district /*Utils::getTaxonomy_term('district')*/, function ($v) use ($province) {
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
                $filterSubDistricts = array_filter( $options_subdistrict /*Utils::getTaxonomy_term('subdistrict')*/ , function ($v) use ($district) {
                    return $v['code4'] == $district;
                });

                foreach ( $filterSubDistricts as $key => $value) {
                    $sub_district_options[$key] = $value['name'];
                } 
            }
            /////////////////  แขวง/ตำบล /////////////////////////////

            ////////////////  รหัสไปรษณีย์  ///////////////////////////
            if(!empty($sub_district)){
                $postal_codes = $options_postal_codes; // Utils::getTaxonomy_term('postal_code');
                $postal_code = $postal_codes[$filterSubDistricts[$sub_district]['ref_pc']];
            }
            ////////////////  รหัสไปรษณีย์  ///////////////////////////
        }

        $form['container']['s1']['province'] = array(
            '#type'         => 'select',
            '#empty_option' => '- เลือก -',
            '#title'        => $this->t('จังหวัด'),
            '#description' => t(''),
            '#default_value'=>  $province,
            '#options'      => $options_provinces, //Utils::getTaxonomy_Term('provinces'),
            '#ajax' => [
                'callback' => '::selectChange', // don't forget :: when calling a class method.
                //'callback' => [$this, 'myAjaxCallback'], //alternative notation
                'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
                'event' => 'change',
                'wrapper' => 'container_s1', // This element is updated with this AJAX callback.
                'progress' => [
                'type' => 'throbber',
                'message' => $this->t(''),
                ],
            ],
        );

        $form['container']['s1']['district'] = array(
            '#type' => 'select',
            '#empty_option' => '- เลือก -',
            '#title' => $this->t('อำเภอ *'),
            '#description' => t(''),
            '#default_value'=> $district,
            '#options'      => $district_options,
            '#attributes'=>array(
                'disabled' => empty($province) ? TRUE : FALSE,
            ),
            '#ajax' => [
              'callback' => '::selectChange', // don't forget :: when calling a class method.
              'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
              'event' => 'change',
              'wrapper' => 'container_s1', // This element is updated with this AJAX callback.
              'progress' => [
                'type' => 'throbber',
                'message' => $this->t(''),
              ],
            ],
        );
      
        $form['container']['s1']['sub_district'] = array(
            '#type' => 'select',
            '#empty_option' => '- เลือก -',
            '#title' => $this->t('แขวง/ตำบล *'),
            '#description' => t(''),
            '#default_value'=> $sub_district,
            '#options'      => $sub_district_options,
            '#attributes'=>array(
                'disabled' => empty($district) ? TRUE : FALSE,
            ),
            '#ajax' => [
                'callback' => '::selectChange', // don't forget :: when calling a class method.
                'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
                'event' => 'change',
                'wrapper' => 'container_s1', // This element is updated with this AJAX callback.
                'progress' => [
                'type' => 'throbber',
                'message' => $this->t(''),
                ],
            ],
        );

        $form['container']['s1']['postal_code'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('รหัสไปรษณีย์'),
            '#description' => t(''),
            '#value' => $postal_code,
            '#attributes'=>array('readonly' => 'readonly'),
        );
        
        /*
        $form['container']['province'] = array(
            '#type'         => 'select',
            '#title'        => $this->t('จังหวัด'),
            '#default_value'=> $data_address_info['province'],
            '#options'      => Utils::getTaxonomy_Term('provinces'),
            '#attributes' => [
                'id' => 'select-province',
                'name' => 'select_province',
              ],
        );
        
        $form['container']['district'] = array(
            '#type'         => 'select',
            '#title'        => $this->t('อำเภอ'),
            // '#default_value'=> $data_address_info['district'],
            '#options'      => Utils::getTaxonomy_Term('district'),
            '#attributes' => [
                'id' => 'select-district',
                'name' => 'select_district',
            ],
        );
        

        $form['container']['sub_district'] = array(
            '#type'         => 'select',
            '#title'        => $this->t('แขวง/ตำบล'),
            // '#default_value'=> $data_address_info['sub_district'],
            '#options'      => Utils::getTaxonomy_Term('subdistrict'),
            '#attributes' => [
                'id' => 'select-sub-district',
                'name' => 'select_sub_district',
            ],
        );

        $form['container']['postal_code'] = array(
            '#type'         => 'select',
            '#title'        => $this->t('รหัสไปรษณีย์'),
            '#default_value'=> $data_address_info['postal_code'],
            '#options'      => Utils::getTaxonomy_Term('postal_code'),
            '#attributes' => [
                'id' => 'select-postal-code',
                'name' => 'select_postal_code',
            ],
        );
        */


        $form['container']['language'] = array(
            '#type'         => 'select',
            '#title'        => $this->t('ภาษาที่ใช้ในการสื่อสาร'),
            '#description' => t(''),
            '#default_value'=> $data_personal_info['language'],
            '#options'      => $options_language,//Utils::getTaxonomy_Term('language'),
            '#attributes' => [
                'id' => 'select-language',
                'name' => 'select_language',
              ],
            '#prefix' => '',
            '#suffix' => '</div></div></div>'
        );

        $form['container']['get_news'] = array(
            '#type' => 'radios',
            '#title' => t('ต้องการรับข่าวสาร'),
            '#default_value'=> $data_personal_info['contact_permission'],
            '#options' => $options_news,//Utils::getTaxonomy_Term('news'),
            '#attributes' => [
              'id' => 'select-get-news',
              'name' => 'select_get_news',
            ],
            '#prefix' => '<div class="row pb-3">
                            <div class="col-12">',
            '#suffix' => ''
        );

        $news_channel = Utils::get_news_channel_arr($data_personal_info['contact_preference']);
        $form['container']['news_channel'] = array(
            '#type' => 'checkboxes',
            '#title'        => $this->t('โดยทาง'),
            '#default_value' => $news_channel,         // Default to unchecked
            // '#return_value' => $ptid,  // Return the value if checked
            '#options'      => Utils::getTaxonomy_term('news_channel'),
            // '#attributes' => [
            //     'id' => 'select-news-channel',
            //     'name' => 'select_news_channel',
            //   ],
            // '#prefix' => '',
            // '#suffix' => ''
        );

        $terms_and_conditions = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(82)->get('field_content')->getValue()[0]['value'];

        $form['container']['accept'] = array(
            '#type' => 'checkbox',
            '#title'        => $this->t('ยอมรับข้อกำหนดและเงื่อนไขการเป็นสมาชิกบิ๊กการ์ด'),
            '#description' => t(''),
            '#default_value' => 0,         // Default to unchecked
            // '#return_value' => $ptid,  // Return the value if checked
            // '#options'      => Utils::getTaxonomy_term('news_channel'),
            // '#prefix' => '',
            // '#suffix' => '',
            '#prefix' => '',
            '#suffix' => '<div id="condition-agree-message">' . $terms_and_conditions . '</div>',
        );

        $form['save'] = array(
            '#type' => 'submit',
            '#name' => 'send',
            '#value' => $this->t('บันทึก'),
            // '#limit_validation_errors' => array(), //  ไม่  verify field
            // '#submit' => array([$this, 'next_form_submit']),
            // '#weight' => 17,
            '#submit' => ['::submitAjax'],
            '#ajax' => [
                'callback' => '::callbackAjax',
                'wrapper' => 'edit-container',
                'progress' => array(
                    // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
                    'type' => 'throbber',
                    // Message to show along progress graphic. Default: 'Please wait...'.
                    'message' => NULL,
                ),
            ],
            '#prefix'=> '',
            '#suffix'=> '<small id="save-button-description1"></small><br>
                        <small id="save-button-description2"></small>
                        </div></div>'
        );

        $time_end = microtime(true);

        //dividing with 60 will give the execution time in minutes otherwise seconds
        $execution_time = $time_end - $time_start;
        // dpm( $execution_time );

        \Drupal::logger('Bigcard')->notice("Personal information : execution_time > " . $execution_time);

        return $form;
    }

    public function selectChange(array &$form, FormStateInterface $form_state) {
        // $bro_from = $form['bro_from']['#value']; 
        // $bro_to = $form['bro_to']['#value']; 
        // dpm($bro_from);
        // dpm($bro_to);
    
        $province = $form_state->getUserInput()['container']['s1']['province'];
    
        $form_state->set('province', $province);
        $form_state->setRebuild();
       
        return $form['container']['s1'];
    }

    public function submitAjax(array &$form, FormStateInterface $form_state) {
        // dpm('submitajax');
        $btn_name   = $form_state->getTriggeringElement()['#name'];
        switch($btn_name){
          case 'forgot_password':{
            $data       = $form_state->get('data');
            if(!empty($data)){
                $forgot_password = $data['forgot_password'];
                $form_state->set('data', array('forgot_password'=>!$forgot_password));
            }else{
                $form_state->set('data', array('forgot_password'=>TRUE));
            }
            
            $form_state->setRebuild();
            break;
          }
        }
      }
    
    public function callbackAjax(array &$form, FormStateInterface $form_state) {
        // dpm('callbackajax');
        $btn_name   = $form_state->getTriggeringElement()['#name'];
        switch($btn_name){
            case 'forgot_password':{
                return  $form['container']['forgot_password']['s1'];
              break;
            }
            case 'send':{
                $ajax_response = new AjaxResponse();
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
                $selector_input = '#edit-container-name';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-name--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-last-name';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-last-name--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#select-gender';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-gender--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#select-date-of-birth';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-bd-date-of-birth--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#select-month-of-birth';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-bd-month-of-birth--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#select-year-of-birth';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-bd-year-of-birth--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-email';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-email--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-address1';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-address1--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-s1-province';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-s1-province--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-s1-district';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-s1-district--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-s1-sub-district';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-s1-sub-district--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-s1-postal-code';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-s1-postal-code--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#select_language';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-language--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#select_language';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-language--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '#edit-container-accept';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#edit-container-accept--description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '[name="container[forgot_password][s1][old_password]"]';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#old-password-description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '[name="container[forgot_password][s1][new_password]"]';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#new-password-description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_input = '[name="container[forgot_password][s1][confirm_new_password]"]';
                $ajax_response->addCommand(new CssCommand($selector_input, $css_clear));
                $selector_description = '#confirm-new-password-description';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                $selector_description = '#save-button-description1';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));
                $selector_description = '#save-button-description2';
                $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

                // --------------------------------------------

                $name = $form_state->getUserInput()['container']['name'];
                $last_name = $form_state->getUserInput()['container']['last_name'];
                $gender = $form_state->getUserInput()['select_gender'];
                $date_of_birth = $form_state->getUserInput()['select_date_of_birth'];
                $month_of_birth = $form_state->getUserInput()['select_month_of_birth'];
                $year_of_birth = $form_state->getUserInput()['select_year_of_birth'];
                $email = $form_state->getUserInput()['container']['email'];
                $address1 = $form_state->getUserInput()['container']['address1'];
                $province = $form_state->getUserInput()['container']['s1']['province'];
                $district = $form_state->getUserInput()['container']['s1']['district'];
                $sub_district = $form_state->getUserInput()['container']['s1']['sub_district'];
                $postal_code = $form_state->getUserInput()['container']['s1']['postal_code'];
                $language = $form_state->getUserInput()['select_language'];
                $accept = $form_state->getUserInput()['container']['accept'];

                $old_password = $form_state->getUserInput()['container']['forgot_password']['s1']['old_password'];
                $new_password = $form_state->getUserInput()['container']['forgot_password']['s1']['new_password'];
                $confirm_new_password = $form_state->getUserInput()['container']['forgot_password']['s1']['confirm_new_password'];

                if(empty($name)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-name';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-name--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($last_name)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-last-name';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-last-name--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }
                
                if(empty($gender)){
                    $pass = FALSE;
                    $selector_input = '#select_gender';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-gender--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($date_of_birth)){
                    $pass = FALSE;
                    $selector_input = '#select-date-of-birth';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-bd-date-of-birth--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($month_of_birth)){
                    $pass = FALSE;
                    $selector_input = '#select-month-of-birth';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-bd-month-of-birth--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($year_of_birth)){
                    $pass = FALSE;
                    $selector_input = '#select-year-of-birth';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-bd-year-of-birth--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }
                
                if(empty($email)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-email';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-email--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($address1)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-address1';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-address1--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($province)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-s1-province';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-s1-province--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($district)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-s1-district';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-s1-district--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($sub_district)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-s1-sub-district';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-s1-sub-district--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($postal_code)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-s1-postal-code';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-s1-postal-code--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($language)){
                    $pass = FALSE;
                    $selector_input = '#select-language';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-language--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                if(empty($accept)){
                    $pass = FALSE;
                    $selector_input = '#edit-container-accept';
                    $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                    $selector_description = '#edit-container-accept--description';
                    $text_description = $this->t('ข้อมูลนี้จำเป็น');
                    $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                }

                $data_forgot_password = $form_state->get('data')['forgot_password'];
                if(!empty($data_forgot_password)){
                    if(empty($old_password)){
                        $pass = FALSE;
                        $selector_input = '[name="container[forgot_password][s1][old_password]"]';
                        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                        $selector_description = '#old-password-description';
                        $text_description = $this->t('ข้อมูลนี้จำเป็น');
                        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                    }

                    if(empty($new_password)){
                        $pass = FALSE;
                        $selector_input = '[name="container[forgot_password][s1][new_password]"]';
                        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                        $selector_description = '#new-password-description';
                        $text_description = $this->t('ข้อมูลนี้จำเป็น');
                        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                    }

                    if(empty($confirm_new_password)){
                        $pass = FALSE;
                        $selector_input = '[name="container[forgot_password][s1][confirm_new_password]"]';
                        $ajax_response->addCommand(new CssCommand($selector_input, $css_input));

                        $selector_description = '#confirm-new-password-description';
                        $text_description = $this->t('ข้อมูลนี้จำเป็น');
                        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                        $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                    }

                    if(!empty($new_password) && !empty($confirm_new_password)){
                        $uppercase = preg_match('@[A-Z]@', $new_password);
                        $lowercase = preg_match('@[a-z]@', $new_password);
                        $number    = preg_match('@[0-9]@', $new_password);
              
                        if(($new_password != $confirm_new_password) || !$uppercase || !$lowercase || !$number || strlen($new_password)<6){
                            $pass = FALSE;
                            $selector_input = '[name="container[forgot_password][s1][new_password]"]';
                            $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
                        
                            $selector_description = '#new-password-description';
                            $text_description = $this->t('-ประกอบด้วยตัวอักษร A-Z และ a-z อย่างละ 1 ตัวขึ้นไป') . '
                            <br>' . $this->t('-มีตัวเลข 0-9 อย่างน้อย 1 ตัวขึ้นไป') . '
                            <br>' . $this->t('-มีความยาวร่วมกัน 6 ตัวขึ้นไป') . '
                            <br>' . $this->t('-ห้ามมีอักษรภาษาไทยและเว้นวรรค') . '
                            <br>' . $this->t('-ตัวอย่าง A1234a, ABCDe1');
                            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                            $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                
                            $selector_input = '[name="container[forgot_password][s1][confirm_new_password]"]';
                            $ajax_response->addCommand(new CssCommand($selector_input, $css_input));
                        
                            $selector_description = '#confirm-new-password-description';
                            $text_description = $this->t('โปรดตรวจสอบให้แน่ใจว่ารหัสผ่านของคุณตรงกัน');
                            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                            $ajax_response->addCommand(new CssCommand($selector_description, $css_description));
                        }
                    }
                    
                }


                if($pass){
                    // $selector_description = '#edit-container-name--description';
                    // $text_description = $data_forgot_password . 'pass' . $pass;
                    // $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));

                    // -------------------------------------------------------------
                    // update personal info
                    $title              = $form_state->getUserInput()['select_prefix_name'];
                    $t_name             = $form['container']['name']['#value']; 
                    $t_last_name        = $form['container']['last_name']['#value'];         
                    $e_name             = $form['container']['name']['#value'];         
                    $e_last_name        = $form['container']['last_name']['#value'];         
                    $gender             = $form_state->getUserInput()['select_gender'];
                    $year_of_birth      = $form_state->getUserInput()['select_year_of_birth'];
                    $month_of_birth     = $form_state->getUserInput()['select_month_of_birth'];
                    $date_of_birth      = $form_state->getUserInput()['select_date_of_birth'];
                    $birth_date         = $year_of_birth . '-' . str_pad($month_of_birth, 2, "0", STR_PAD_LEFT) . '-' . str_pad($date_of_birth, 2, "0", STR_PAD_LEFT);
                    $contact_permission = $form_state->getUserInput()['select_get_news'];
                    $contact_preference = $form_state->getUserInput()['container']['news_channel'];
                    $news_channel_number = Utils::get_news_channel_number($contact_preference);
                    $language            = $form_state->getUserInput()['select_language'];
                    $occupation          = $form['container']['occupation']['#value'];         
                    $welfare_id          = $form['container']['welfare_id']['#value'];         
                    
                    $data_obj = [
                        "title" => $title,
                        "tName" => $t_name,
                        "tLastName" => $t_last_name,
                        "eName" => $e_name, // จะรู้ได้ไง มีให้ช่องเดียว
                        "eLastName" => $e_last_name, // จะรู้ได้ไง มีให้ช่องเดียว
                        "gender" => $gender,
                        "birthDate" => $birth_date,
                        // "nationality" => "1",
                        // "nationalityOther" => "",
                        // "familySize" => "",
                        "contactPermission" => $contact_permission,
                        "contactPreference" => $news_channel_number,
                        "language" => $language, // ใช่ dropdown ภาษาที่ใช้ในการสื่อสาร รึป่าว
                        "occupation" => $occupation,
                        "welfareId" => $welfare_id
                    ];
                    // dpm($data_obj);
                    $data_update_personal_info = Utils::update_personal_info_api($data_obj);
            
                    // ------------------------------------------------------------
                    // update address
                    if($data_update_personal_info['code'] == '200'){
                        // $add_type        = $form['container']['add_type']['#value'];
                        $address1           = $form['container']['address1']['#value']; 
                        $address2           = $form['container']['address2']['#value']; 
                        $moo                = $form['container']['moo']['#value']; 
                        $room_no            = $form['container']['room_no']['#value']; 
                        $soi                = $form['container']['soi']['#value']; 
                        $road               = $form['container']['road']['#value']; 
                        $sub_district       = $form_state->getUserInput()['container']['s1']['sub_district'];
                        $district           = $form_state->getUserInput()['container']['s1']['district'];
                        $province           = $form_state->getUserInput()['container']['s1']['province'];
                        $postal_code        = $form['container']['s1']['postal_code']['#value'];
                        // $country         = $form['container']['country']['#value'];
                        $home_phone         = $form['container']['tel_home']['home_phone']['#value']; 
                        $home_phone_ext     = $form['container']['tel_home']['home_phone_ext']['#value']; 
                
                        $data_obj = [
                            "addType" => '1',  // เอามาจากไหน
                            "address1" => $address1,
                            "address2" => $address2,
                            "moo" => $moo,
                            "roomNo" => $room_no,
                            "soi" => $soi,
                            "road" => $road,
                            "subDistrict" => $sub_district,
                            "district" => $district,
                            "province" => $province,
                            "postalCode" => $postal_code,
                            "country" => 'TH', // เอามาจากไหน
                            "homePhone" => $home_phone,
                            "homePhoneExt" => $home_phone_ext,
                        ];
                        // dpm($data_obj); 
                        $data_update_address_info = Utils::update_address_info_api($data_obj);
                    }
            
                    // ------------------------------------------------------------------
                    // update email
                    if($data_update_address_info['code'] == '200'){
                        $email           = $form['container']['email']['#value']; 
                        
                        $data_obj = [
                            "email" => $email, 
                            "isVerifyEmail" => $this->data_personal_info['is_verify_email'], // เอามาจากไหน ตอนคอล personalInfo หรอ
                            "verifyEmailDate" => (empty($this->data_personal_info['verify_email_date'])) ? date("Y-m-d h:m:s") : $this->data_personal_info['verify_email_date']
                        ];
                        // dpm($data_obj);
                        $data_update_email = Utils::update_email_api($data_obj);
                    }
            
                    // ------------------------------------------------------------------
                    // sms update data
                    if($data_update_email['code'] == '200'){
                        $mobile_phone = $this->data_personal_info['mobile_phone'];
                        $data_sms_update_data = Utils::sms_update_data_api($mobile_phone, $language);

                        $selector_description = '#save-button-description1';
                        $text_description = 'Update information successfully';
                        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                        // drupal_set_message("Update succesfully.");
                    }else{
                        $selector_description = '#save-button-description1';
                        $text_description = 'Update information fail ' . json_encode($data_update_personal_info) . json_encode($data_update_address_info) . json_encode($data_update_email);
                        $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                    }

                    // ------------------------------------------------------------------
                    // update password
                    if(!empty($data_forgot_password)){
                        $old_password               = $form['container']['forgot_password']['s1']['old_password']['#value']; 
                        $new_password               = $form['container']['forgot_password']['s1']['new_password']['#value'];         

                        $data_obj = [
                            "oldPassword" => $old_password,
                            "newPassword" => $new_password,
                        ];
                        $data_update_password = Utils::update_password_api($data_obj);

                        if($data_update_password['code'] == '200'){
                            // update drupal password
                            $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
                            // Set the new password
                            $user->setPassword($new_password);
                            $user->save();

                            $selector_description = '#save-button-description2';
                            $text_description = 'Change password successfully';
                            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                        }else{
                            $selector_description = '#save-button-description2';
                            $text_description = 'Change password : ' . json_encode($data_update_password);
                            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
                        }
                    }
                }
                return $ajax_response;
              break;
            }
        }
        return $form;
      }

    /**
     * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // dpm('in submit form');
        /*
        $title              = $form_state->getUserInput()['select_prefix_name'];
        $t_name             = $form['container']['name']['#value']; 
        $t_last_name        = $form['container']['last_name']['#value'];         
        $e_name             = $form['container']['name']['#value'];         
        $e_last_name        = $form['container']['last_name']['#value'];         
        $gender             = $form_state->getUserInput()['select_gender'];
        $year_of_birth      = $form_state->getUserInput()['select_year_of_birth'];
        $month_of_birth     = $form_state->getUserInput()['select_month_of_birth'];
        $date_of_birth      = $form_state->getUserInput()['select_date_of_birth'];
        $birth_date         = $year_of_birth . '-' . str_pad($month_of_birth, 2, "0", STR_PAD_LEFT) . '-' . str_pad($date_of_birth, 2, "0", STR_PAD_LEFT);
        $contact_permission = $form_state->getUserInput()['select_get_news'];
        $contact_preference = $form_state->getUserInput()['container']['news_channel'];
        $news_channel_number = Utils::get_news_channel_number($contact_preference);
        $language            = $form_state->getUserInput()['select_language'];
        $occupation          = $form['container']['occupation']['#value'];         
        $welfare_id          = $form['container']['welfare_id']['#value'];         
        
        $data_obj = [
            "title" => $title,
            "tName" => $t_name,
            "tLastName" => $t_last_name,
            "eName" => $e_name, // จะรู้ได้ไง มีให้ช่องเดียว
            "eLastName" => $e_last_name, // จะรู้ได้ไง มีให้ช่องเดียว
            "gender" => $gender,
            "birthDate" => $birth_date,
            // "nationality" => "1",
            // "nationalityOther" => "",
            // "familySize" => "",
            "contactPermission" => $contact_permission,
            "contactPreference" => $news_channel_number,
            "language" => $language, // ใช่ dropdown ภาษาที่ใช้ในการสื่อสาร รึป่าว
            "occupation" => $occupation,
            "welfareId" => $welfare_id
        ];
        // dpm($data_obj);
        $data_update_personal_info = Utils::update_personal_info_api($data_obj);

        // ------------------------------------------------------------
        
        // $add_type        = $form['container']['add_type']['#value'];
        $address1           = $form['container']['address1']['#value']; 
        $address2           = $form['container']['address2']['#value']; 
        $moo                = $form['container']['moo']['#value']; 
        $room_no            = $form['container']['room_no']['#value']; 
        $soi                = $form['container']['soi']['#value']; 
        $road               = $form['container']['road']['#value']; 
        $sub_district       = $form_state->getUserInput()['container']['s1']['sub_district'];
        $district           = $form_state->getUserInput()['container']['s1']['district'];
        $province           = $form_state->getUserInput()['container']['s1']['province'];
        $postal_code        = $form['container']['s1']['postal_code']['#value'];
        // $country         = $form['container']['country']['#value'];
        $home_phone         = $form['container']['tel_home']['home_phone']['#value']; 
        $home_phone_ext     = $form['container']['tel_home']['home_phone_ext']['#value']; 

        $data_obj = [
            "addType" => '1',  // เอามาจากไหน
            "address1" => $address1,
            "address2" => $address2,
            "moo" => $moo,
            "roomNo" => $room_no,
            "soi" => $soi,
            "road" => $road,
            "subDistrict" => $sub_district,
            "district" => $district,
            "province" => $province,
            "postalCode" => $postal_code,
            "country" => 'TH', // เอามาจากไหน
            "homePhone" => $home_phone,
            "homePhoneExt" => $home_phone_ext,
        ];
        dpm($data_obj); 
        $data_update_address_info = Utils::update_address_info_api($data_obj);

        // ------------------------------------------------------------------
        $email           = $form['container']['email']['#value']; 
        $data_obj = [
            "email" => $email, 
            "isVerifyEmail" => $this->data_personal_info['is_verify_email'], // เอามาจากไหน ตอนคอล personalInfo หรอ
            "verifyEmailDate" => $this->data_personal_info['verify_email_date'] // เอามาจากไหน ตอนคอล personalInfo หรอ
        ];
        // dpm($data_obj);
        $data_update_email = Utils::update_email_api($data_obj);

        // ------------------------------------------------------------------
        $mobile_phone = $this->data_personal_info['mobile_phone'];
        // sms update data
        $data_sms_update_data = Utils::sms_update_data_api($mobile_phone, $language);
        */
    }
}
