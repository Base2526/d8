<?php

namespace Drupal\bigcard\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\RedirectCommand;

use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\bigcard\Utils\Utils;


/**
 * SendToDestinationsForm class.
 */
class MyCouponsModalForm extends FormBase {

  private $coupon_id;
  /**
   * {@inheritdoc}
   */

  public function __construct() {
    $this->language = \Drupal::languageManager()->getCurrentLanguage()->getId();
  }

  public function getFormId() {
    return 'my_coupons_modal_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {

    $route_match = \Drupal::service('current_route_match');
    $this->coupon_id   = $route_match->getParameter('coupon_id');
    // dpm($coupon_id);

    $form['#prefix'] = '<div id="modal_example_form">';
    $form['#suffix'] = '</div>';

    /*
    // The status messages that will contain any form errors.
    $form['status_messages'] = [
      '#type' => 'status_messages',
      '#weight' => -10,
    ];

    // A required checkboxes field.
    $form['select'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select Destination(s)'),
      '#options' => ['random_value' => 'Some random value'],
      '#required' => TRUE,
    ];

    $form['actions'] = array('#type' => 'actions');
    $form['actions']['send'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit modal form'),
      '#attributes' => [
        'class' => [
          'use-ajax',
        ],
      ],
      '#ajax' => [
        'callback' => [$this, 'submitModalFormAjax'],
        'event' => 'click',
      ],
    ];
    */

    $form['messages'] = array(
      '#type' => 'markup',
      '#markup' => $this->t('โปรดแสดงคูปองส่วนลดนี้ที่จุดชำระสินค้าและต่อหน้าพนักงานแคชเชียร์ สาขาที่ร่วมรายการเท่านั้น รหัสมีอายุ 15 นาที หลังจากกดปุ่มยืนยัน')
    );

    $form['actions']['ok'] = [
      '#type' => 'submit',
      '#name' => 'ok',
      '#value' => $this->t('ตกลง'),
      '#submit' => [$this, 'submitAjax'],
      '#ajax' => [
        'callback' => [$this, 'callbackAjax'],
        'event' => 'click',
      ],
    ];

    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#name' => 'cancel',
      '#value' => $this->t('ยกเลิก'),
      '#submit' => [$this, 'submitAjax'],
      '#ajax' => [
        'callback' => [$this, 'callbackAjax'],
        'event' => 'click',
      ],
    ];

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $form;
  }

  public function submitAjax(array &$form, FormStateInterface $form_state) {
    // drupal_set_message("s1confirm_form_submit.");

    $btn_name   = $form_state->getTriggeringElement()['#name'];
    switch($btn_name){
      case 'ok':{
        // Utils::use_coupon_api($this->coupon_id);
      break;
      }
    }

  }

  /**
   * AJAX callback handler that displays any errors or a success message.
   */
  public function callbackAjax(array $form, FormStateInterface $form_state) {

    $btn_name   = $form_state->getTriggeringElement()['#name'];

    $ajax_response = new AjaxResponse();

    switch($btn_name){
      case 'ok':{
        /*
        $route_match = \Drupal::service('current_route_match');
        $coupon_id = $route_match->getParameter('coupon_id');
        // dpm($coupon_id);
        
        $pass = TRUE;

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
            // $ajax_response->addCommand(new HtmlCommand($selector_description, $coupon_id));
        // }
        */

        $command = new CloseModalDialogCommand();
        $ajax_response->addCommand($command);
        return $ajax_response;
      break;
      }

      case 'cancel':{

        $command = new CloseModalDialogCommand();
        $ajax_response->addCommand($command);
        return $ajax_response;
      break;
      }
    }
  }

  // public function callbackAjaxCancel(array $form, FormStateInterface $form_state) {
  //   $ajax_response = new AjaxResponse();
  //   $command = new CloseModalDialogCommand();
  //   $ajax_response->addCommand($command);
  //   return $ajax_response;
  // }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['config.modal_form_example_modal_form'];
  }

}
