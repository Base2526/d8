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
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\RedirectCommand;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Endroid\QrCode\QrCode;

use Drupal\bigcard\Utils\Utils;

class CouponDetailForm extends FormBase {
    private $data_get_coupon_detail;
    private $is_ocp;

    /**
     * {@inheritdoc}.
    */
    public function __construct() {
        $this->language = \Drupal::languageManager()->getCurrentLanguage()->getId();

        $global_config = \Drupal\config_pages\Entity\ConfigPages::config('global_config');
        if(isset( $global_config )){
            $this->is_debug =  $global_config->get('field_is_debug')->value;
        }

        $route_match = \Drupal::service('current_route_match');
        $coupon_id   = $route_match->getParameter('coupon_id');

        // dpm( $this->t('coupon_id : @coupon_id', array('@coupon_id'=>$coupon_id)) );
        // dpm($coupon_id);

        $this->is_ocp = explode("&", $coupon_id)[1];

        $this->data_get_coupon_detail = Utils::get_coupon_detail_api( $this->is_ocp,  (explode("&", $coupon_id)[0])  );

        
        // dpm($this->data_get_coupon_detail);

        // dpm(( explode("&", $coupon_id) ));
    }

    public function getFormId() {
        return 'coupon_detail_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // $form['container']['personal_information']['sex'] = array(
        //     '#type'         => 'radios',
        //     '#default_value'=> 0,
        //     '#options'      => ['คูปองบิ๊กซี', 'คูปองร้านค้า'],
        // );

        // dpm($_SESSION['auth_token']);

        // dpm(rand());
        
        $data_get_coupon_detail = $this->data_get_coupon_detail;

        // dpm($data_my_coupon);
        // dpm($data_get_coupon_detail);
        $data = $data_get_coupon_detail['data'];

        $form['header'] = array(
            '#type' => 'markup',
            '#markup' => '<div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <img class="w-100" src="https://i.ya-webdesign.com/images/coupon-clipart-transparent-8.png">'
        );

        /*
        $form['redeem_btn'] = array(
            '#type' => 'submit',
            '#name' => 'login',
            '#value' => t('กดเพื่อรับสิทธิ์'),
            '#submit' => ['::submitAjax'],
            '#ajax' => [
              'callback' => '::callbackAjax',
              'wrapper' => 'redeem-btn-container',
              'progress' => array(
                  // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
                  'type' => 'throbber',
                  // Message to show along progress graphic. Default: 'Please wait...'.
                  'message' => NULL,
              ),
            ],
            '#prefix'   => '<div id="redeem-btn-container">
                                <div class="coupon-detail-redeem available-btn">
                                <div class="text1">',
            '#suffix'   => '</div>
                            <div class="text2 fs-xxs">รหัสมีอายุ 15 นาที</div>
                        </div>'
        );
        */

        $form['redeem_btn'] =   [
                                    '#type' => 'link',
                                    '#title' => $this->t('กดเพื่อรับสิทธิ์'),
                                    '#url' => Url::fromRoute('my_coupon_modal.form', ['coupon_id' => $data['couponId']]),
                                    '#attributes' => [
                                        'class' => [
                                            'use-ajax',
                                            'button',
                                        ],
                                    ],
                                    '#prefix'   => '<div id="redeem-btn-container">
                                                        <div class="coupon-detail-redeem available-btn">
                                                        <div class="text1">',
                                    '#suffix'   => '</div>
                                                        <div class="text2 fs-xxs" style="margin-top: 10px;">รหัสมีอายุ 15 นาที</div>
                                                    </div>'
                                ];

        // <div class="coupon-detail-redeem available-btn">
        //     <div class="text1">กดเพื่อรับสิทธิ์</div>
        //     <div class="text2 fs-xxs">รหัสมีอายุ 15 นาที</div>
        // </div>

        $code;
        if( empty($this->is_ocp) ){
            $code = $data['couponId'];
        }else{
            $code = $data['couponCode'];
        }

        //----------------- Barcode --------------------
        $redColor = [255, 0, 0];
        $generator = new BarcodeGeneratorPNG();
        $src_barcode = Utils::base64ToImage("data:image/png;base64," .base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128)), base64_encode(\Drupal::currentUser()->id()) . '_barcode.png');
        //----------------- Barcode --------------------

        //----------------- QrCode --------------------
        $qrCode = new QrCode($code);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setLogoSize(150, 200);
        $qrCode->setValidateResult(false);

        // Save it to a file
        $qrCode->writeFile(__DIR__.'/qrcode.png');

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $qrCode->writeDataUri();
        $src_qrcode = Utils::base64ToImage($dataUri, base64_encode(\Drupal::currentUser()->id()) . '_qrcode.png');
        //----------------- QrCode --------------------


        $qr_bar_code = '<div>
                            <div>กรุณาแสดงรหัสนี้ให้เแก่พนักงานเพื่อรับสิทธิ์</div>
                            <div><img src="'. $src_barcode .'" /></div>
                            <div><img src="'. $src_qrcode .'" /></div>
                        </div>';

        $detail = '
        
                                <div class="coupon-detail-redeem counter-btn">
                                    <div class="text1 fs-xxs">คุณเหลือเวลาอีก</div>
                                    <div class="text2 timer">00:15:00</div>
                                </div>
                                <div class="coupon-detail-redeem expired-btn">
                                    <div class="text1">คุณใช้สิทธิ์นี้แล้วค่ะ</div>
                                </div>
                                <div id="warning-text" class="fs-xxs"></div>
                            </div>
                            <div id="redeem-text" class="fs-xxs">เวลาที่เริ่มกดรับสิทธิ์ : <span id="redeem-time"></span></div>
                           '.  $qr_bar_code  .'
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="coupon-detail-name">' . $data['couponName'] . '</div>
                            <div class="coupon-detail-condition">
                            <ul>';
        
        foreach($data['condition'] as $condition_item){
            $detail .= '<li class="fs-xs">' . $condition_item['message'] . '</li>';
        }
        // Utils::mapping_date($data['endDate'], $this->language)
        $detail .= '</ul>
                    <div class="coupon-detail-exp fs-sm">วันหมดอายุคูปอง : ' . $data['endDate'] . '</div>
                    </div></div>';

        
        $form['aaa'] = array(
            '#type' => 'markup',
            '#markup' => $detail
        );

        $form['coupon_status'] = array(
            '#type' => 'hidden',
            '#value' => $data['couponStatus']
        );

        $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

        return $form;
    }

    public function submitAjax(array &$form, FormStateInterface $form_state) {
        // drupal_set_message("s1confirm_form_submit.");
    }
    
    public function callbackAjax(array &$form, FormStateInterface $form_state) {
        $ajax_response = new AjaxResponse();
        $pass = TRUE;

        $coupon_id = $this->data_get_coupon_detail['data']['couponId'];

    
        $text_description = 'ข้อมูลนี้จำเป็น';
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
        $selector_description = '#redeem-time';
        $ajax_response->addCommand(new HtmlCommand($selector_description, ''));

        $selector_description = '#warning-text';
        $ajax_response->addCommand(new HtmlCommand($selector_description, ''));
        // --------------------------------------------
        
        // $data_use_coupon = Utils::use_coupon_api($coupon_id);

        // 0 = คูปองหมายเลขนี้สำมำรถใช้งำนได้
        // 1 = หมายเลขคูปองนี้ถูกใช้งำนไปแล้ว
        // 2 = ไม่พบหมายเลขคูปองนี้ในระบบ
        // if($data_use_coupon['data_code'] == '0'){
            $selector_description = '#redeem-time';
            $text_description = Utils::mapping_date(date("Y-m-d"), $this->language) . ' เวลา ' . date("h:i:s");
            $ajax_response->addCommand(new HtmlCommand($selector_description, $text_description));
        // }else{
            // $selector_description = '#warning-text';
            // $ajax_response->addCommand(new HtmlCommand($selector_description, $data_use_coupon['data_code']));
        // }

        return $ajax_response;
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
        
    }
}
