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
use Drupal\config_pages\Entity\ConfigPages;

use Drupal\bigcard\Utils\Utils;

class ExclusiveForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'exclusive_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        // $form['left']['img_card'] = array(
        // '#type'     =>'markup',
        // '#markup'   =>'** exclusive_form',            
        // );

        // return $form;
        
        $exclusive = ConfigPages::config('exclusive');
        $content = $exclusive->get('field_exclusive')->getValue()[0]['value'];

        // dpm($content);
        if(!empty($content)){
            $form['content'] = array(
                '#type'     =>'markup',
                '#markup'   => $content,             
            );
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
        
    }
}
