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
use Drupal\bigcard\Utils\Utils;


class MyBigcardForm extends FormBase {

    /**
     * {@inheritdoc}.
     */

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->language = \Drupal::languageManager()->getCurrentLanguage()->getId();

        $global_config = \Drupal\config_pages\Entity\ConfigPages::config('global_config');
        if(isset( $global_config )){
            $this->is_debug =  $global_config->get('field_is_debug')->value;
        }
    }

    public function getFormId() {
        return 'my_bigcard_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $data_point_balance = Utils::point_balance_api();
        $data_point_expire = Utils::point_expire_api();
        $data_personal_info = Utils::personal_info_api();

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

        $form['container'] = array(
            '#type' => 'container',
        );

        $form['container']['left'] = array(
            '#type' => 'container',
            '#prefix' => '<div class="row">
                            <div class="col-12 fs-xl fw-400 py-2">
                                <i class="far fa-user fs-md fw-600"></i>  ' . $this->t('บัญชีบิ๊กการ์ดของฉัน') . '
                            </div>
                        </div>
                        <div id="my-bg-form" class="row box-account">
                            <div class="bigcard_img col-lg-5 col-md-5 col-sm-12 col-12">',
            '#suffix' => '</div>',  
        );

        $form['container']['left']['img_card'] = array(
            '#type'     =>'markup',
            '#markup'   =>'<img width="300px" style="border: 1px #ccc solid;margin: 10px;" id="image_v" src="/sites/default/modules/customs/bigcard/images/bigcard-account-new.png" alt="picture">',
            '#prefix' => '',
            '#suffix' => '',               
        );

        $form['container']['right'] = array(
            '#type' => 'container',
            '#prefix' => '<div class="box_account_info col-lg-7 col-md-7 col-sm-12 col-12">',
            '#suffix' => '</div>', 
        );

        $form['container']['right']['name'] = array(
            '#type' => 'markup',
            '#markup' => $data_personal_info['name'] . ' ' . $data_personal_info['last_name'],
            '#prefix' => '<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10 col-10">',
            '#suffix' => '</div>', 
        );

        $form['container']['right']['edit'] = array(
            '#type' => 'link',
            '#title' => $this->t('แก้ไข'),
            '#weight' => 0,
            '#url' => Url::fromRoute('personal_information.form'),
            '#prefix' => '<div id="my-bg-edit-link" class="col-lg-2 col-md-2 col-sm-2 col-2 text-right">',
            '#suffix' => '</div></div>', 
        );

        $bigcard_no = Utils::formatted_bigcard_number($data_point_balance['bigcard']);
        $form['container']['right']['number_bigcard'] = array(
            '#type'     =>'markup',
            '#markup'   => $bigcard_no,
            '#prefix' => '<div id="my-bg-member-number" class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 fs-sm bold">
                                ' . $this->t('หมายเลขสมาชิกบิ๊กการ์ด') . '
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 fs-sm text-right">',
            '#suffix' => '</div></div><div class="line-bottom"></div>',               
        );

        $form['container']['right']['score'] = array(
            '#type' => 'container',
        );

        $form['container']['right']['score']['line1'] = array(
            '#type'     =>'markup',
            '#markup'   => $data_point_balance['point_balance'],
            '#prefix' => '<div class="row">
                            <div class="col-12 bold">
                                ' . $this->t('คะแนน') . '
                            </div>
                        </div>
                        <div id="my-bg-total-score" class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                ' . $this->t('คะแนนสะสมรวมทั้งหมด') . '
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-right">',
            '#suffix' => '</div></div>',               
        );

        $expire_date = Utils::mapping_date($data_point_expire['expire_date'], $this->language);
        $form['container']['right']['score']['line2'] = array(
            '#type'     =>'markup',
            '#markup'   => $expire_date,
            '#prefix' => '<div id="my-bg-score-expire-date" class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                ' . $this->t('คะแนนปกติหมดอายุ') . '
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-right">',
            '#suffix' => '</div></div><div class="line-bottom"></div>',               
        );
      
        $form['container']['right']['note'] = array(
            '#type' => 'container',
        );

        $form['container']['right']['note']['message1'] = array(
            '#type'     =>'markup',
            '#markup'   => $data_point_expire['point_amount'] . ' ' . $this->t('คะแนน'),
            '#prefix' => '<div id="my-bg-score-expire-soon" class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-8 fs-xs italic">
                            ' . $this->t(' *คะแนนหมดอายุเร็วสุดคำนวณถึงเมือวานนี้') . '
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-4 text-right fs-xs italic">',
            '#suffix' => '</div></div>',           
        );

        $message2 = Utils::mapping_date($data_point_expire['expire_date'], $this->language);
        $form['container']['right']['note']['message2'] = array(
            '#type'     =>'markup',
            '#markup'   => $message2,
            '#prefix' => '<div id="my-bg-score-expire-soon-date" class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-8 fs-xs italic">
                                ' . $this->t('หมดอายุวันที่') . '
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-4 text-right fs-xs italic">',
            '#suffix' => '</div></div>',           
        );

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
        
    }
}
