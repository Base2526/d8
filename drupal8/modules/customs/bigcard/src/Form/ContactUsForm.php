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

class ContactUsForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'contact_us_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['#tree'] = TRUE;
        $form['container'] = array(
            '#type' => 'container',
            '#prefix' => '<div id="container">',
            '#suffix' => '</div>',
        );

        $form['container']['left'] = array(
            '#type' => 'container',
            '#prefix' => '<div id="container-left">',
            '#suffix' => '</div>',
        );
        $form['container']['left']['text'] = array(
            '#type' => 'item',
            '#markup' => 'ติดต่อเรา
            สำนักงานใหญ่
            บริษัทบิ๊กซี ซูเปอร์เซ็นเตอร์ จำกัด(มหาชน)
            ชั้น 7 อาคารบิ๊กซีราชดำริ
            97/11 ถนน ราชดำริ
            แขวงลุมพินี เขต ปทุมวัน
            กรุงเทพมหานคร 10330',
            '#prefix' => '<div> ติดต่อเรา ',
            '#suffix' => '</div>',
          );

        $form['container']['right'] = array(
            '#type' => 'container',
            '#prefix' => '<div id="container-right">แบบฟอร์มการติดต่อ',
            '#suffix' => '</div>',
        );

        $form['container']['right']['name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('ชื่อ-นามสกุล'),
            '#prefix' => '',
            '#suffix' => '',
        );

        $form['container']['right']['email'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('อีเมลล์'),
            '#prefix' => '',
            '#suffix' => '',
        );

        $form['container']['right']['tel'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('เบอร์โทรศัพท์มือถือ'),
            '#prefix' => '',
            '#suffix' => '',
        );

        $form['container']['right']['topic'] = array(
            '#type'         => 'select',
            '#empty_option' => '- เลือก -',
            '#title'        => $this->t('หัวข้อ'),
            '#options'      => Utils::getTaxonomy_term('contact_us_topic'),
        );
 
        $form['container']['right']['comment'] = array(
            '#title' => t('รายละเอียด'),
            '#type' => 'textarea',
            '#default_value' => '',
        );

        $form['container']['right']['save'] = array(
            '#type' => 'submit',
            '#name' => 'send',
            '#value' => $this->t('ยืนยัน'),
            // '#limit_validation_errors' => array(), //  ไม่  verify field
            // '#submit' => array([$this, 'next_form_submit']),
            // '#weight' => 17,
            '#prefix'=> '',
            '#suffix'=> ''
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
