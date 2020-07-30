<?php

/**
 * @file
 * Contains \Drupal\demo\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\bigcard\Form\MultistepNewMember;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\bigcard\Utils\Utils;


class Step4CompleteForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'step_4_complete_form';
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
                              <i class="far fa-check-circle opaque"></i>
                              <a href="./step2" title="' . $this->t('ข้อมูลสำหรับเข้าสู่ระบบ') . '"><span>2. </span>' . $this->t('ข้อมูลสำหรับเข้าสู่ระบบ') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_password" class="pending">
                              <i class="far fa-check-circle opaque"></i>
                              <a href="./step3" title="' . $this->t('รหัสผ่าน') . '"><span>3. </span>' . $this->t('รหัสผ่าน') . '</a>
                          </li>
                          <li style="float: left; width: 25%;" id="step_complete" class="pending">
                              <i class="fas fa-pencil-alt pencil-icon opaque"></i>
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

    $form['left']['wizard'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="row">
                        <div class="col-12">
                        ' .  $this->get_wizard() . '
                        </div>
                      </div>',
    );

    $form['left']['img_card'] = array(
      '#type'     =>'markup',
      '#markup'   =>'<div class="row container-px py-3">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right">
                          <img width="340px" style="border: 1px #ccc solid;margin: 10px;" id="image_v" src="/sites/default/modules/customs/bigcard/images/big_card.png">
                        </div>',
      '#prefix' => '',
      '#suffix' => '',               
    );

    $form['right']['m1'] = array(
      '#type'     =>'markup',
      '#markup' => '<div class="col-lg-6 col-md-6 col-sm-12 col-12">
                      <div class="fs-lg fw-700">' . $this->t('สมัครบัญชีออนไลน์เรียบร้อยแล้ว') . '</div>
                      <div class="fs-lg fw-700">' . $this->t('ยินดีต้อนรับสู่ครอบครัวบิ๊กการ์ด ออนไลน์') . '</div>
                      <div class="line-bottom">&nbsp;</div>',
      '#suffix' => '',               
    );

    // $form['right']['log'] = array(
    //   '#type'     =>'markup',
    //   '#markup' => $this->store->get('data_register'),
    //   '#prefix' => '<div class="row">',
    //   '#suffix' => '</div>',               
    // );

    $title = Utils::getTaxonomy_Term('prefix_name')[$this->store->get('title')];
    $name = $this->store->get('name');
    $last_name = $this->store->get('last_name');

    $form['right']['name'] = array(
      '#type'     =>'markup',
      '#markup' => $title . ' ' . $name . ' ' . $last_name,
      '#prefix' => '<div class="fs-lg fw-700">',
      '#suffix' => '</div>',               
    );

    $bigcard = $this->store->get('data_register')['bigcard'];
    $bigcard_no = Utils::formatted_bigcard_number($bigcard);
    $form['right']['number_id_card'] = array(
      '#type'     =>'markup',
      '#markup'   => $bigcard_no,
      '#prefix' => '<span class="fs-md fw-400">' . $this->t('หมายเลขสมาชิกบิ๊กการ์ด') . '</span>
                    <span class="fs-md fw-700">',
      '#suffix' => '</span>',               
    );

    $form['right']['coupon_text'] = array(
      '#type'     =>'markup',
      '#markup'   =>'<div class="fs-lg fw-400">' . $this->t('ตรวจสอบคูปองได้ที่') . '</div>',
      '#prefix' => '',
      '#suffix' => '',                   
    );

    $form['right']['left']['coupon_link'] = array(
      '#type' => 'link',
      '#title' => $this->t('คูปองของฉัน'),
      '#url' => Url::fromRoute('my_coupons.form'),
      '#prefix' => '',
      '#suffix' => '</div></div>',  
    );

    // $form['actions']['sale_online'] = array(
    //   '#type' => 'link',
    //   '#title' => $this->t('ซื้อสินค้าบิ๊กซีออนไลน์'),
    //   '#attributes' => array(
    //     'class' => array('button'),
    //   ),
    //   '#weight' => 0,
    //   '#url' => Url::fromRoute('demo.multistep_one'),
    // );

    // $form['actions']['my_big_card'] = array(
    //   '#type' => 'link',
    //   '#title' => $this->t('เข้าสู่บัญชีบิ๊กการ์ดของฉัน'),
    //   '#attributes' => array(
    //     'class' => array('button'),
    //   ),
    //   '#weight' => 0,
    //   '#url' => Url::fromRoute('demo.multistep_one'),
    // );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $this->store->set('age', $form_state->getValue('age'));
    // $this->store->set('location', $form_state->getValue('location'));

    // Save the data
    parent::saveData();
    $form_state->setRedirect('some_route');
  }
}
