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

class MemberBenefitsForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'member_benefits_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['left']['img_card'] = array(
        '#type'     =>'markup',
        '#markup'   =>'<div class="row">
                            <div class="col-12 fs-xl fw-400 py-2">
                                <i class="far fa-star fs-md fw-600"></i>  ' . $this->t('สิทธิประโยชน์สมาชิก') . '
                            </div>
                        </div>
                        <div class="line-bottom"></div>
                        <div class="row">
                            <div class="col-12 py-3">
                                <img class="w-100" alt="" src="/sites/default/modules/customs/bigcard/images/bigcard-benefits.jpg">
                            </div>
                        </div>',            
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
