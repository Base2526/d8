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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\bigcard\Utils\Utils;



class TermsAndConditionsForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'terms_and_conditions_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $lang = 'th';

        $header1 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(49)->get('field_name_' . $lang)->getValue()[0]['value'];
        $header2 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(50)->get('field_name_' . $lang)->getValue()[0]['value'];
        $header3 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(51)->get('field_name_' . $lang)->getValue()[0]['value'];
        $header4 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(52)->get('field_name_' . $lang)->getValue()[0]['value'];
        
        $content1 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(49)->get('field_content_' . $lang)->getValue()[0]['value'];
        $content2 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(50)->get('field_content_' . $lang)->getValue()[0]['value'];
        $content3 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(51)->get('field_content_' . $lang)->getValue()[0]['value'];
        $content4 = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(52)->get('field_content_' . $lang)->getValue()[0]['value'];

        $form['left']['img_card'] = array(
        '#type'     =>'markup',
        '#markup'   =>'<div class="row">
                            <div class="col-12 fs-xl fw-400 py-2">
                                <i class="fas fa-bars fs-md fw-600"></i>  ข้อกำหนดและเงื่อนไข
                            </div>
                        </div>
                        <div class="line-bottom"></div>',                     
        );

        $form['accordion'] = array(
            '#type' => 'markup',
            '#markup' => '<div class="container py-3">
            <div id="accordion">
              <div class="card">
                <div class="card-header">
                  <a class="card-link fs-sm" data-toggle="collapse" href="#collapseOne">
                    <i class="fa" aria-hidden="true"></i>&nbsp;' . $header1 . '
                  </a>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                  <div class="card-body fs-sm">
                    ' . $content1 . '
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link fs-sm" data-toggle="collapse" href="#collapseTwo">
                    <i class="fa" aria-hidden="true"></i>&nbsp;' . $header2 . '
                  </a>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                  <div class="card-body fs-sm">
                  ' . $content2 . '
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link fs-sm" data-toggle="collapse" href="#collapseThree">
                    <i class="fa" aria-hidden="true"></i>&nbsp;' . $header3 . '
                  </a>
                </div>
                <div id="collapseThree" class="collapse" data-parent="#accordion">
                  <div class="card-body fs-sm">
                  ' . $content3 . '
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link fs-sm" data-toggle="collapse" href="#collapseFour">
                    <i class="fa" aria-hidden="true"></i>&nbsp;' . $header4 . '
                  </a>
                </div>
                <div id="collapseFour" class="collapse" data-parent="#accordion">
                  <div class="card-body fs-sm">
                  ' . $content4 . '
                  </div>
                </div>
              </div>
            </div>
          </div>'
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
