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

class WhatisBigcardForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'what_is_bigcard_form';
    }

     /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $route_match = \Drupal::service('current_route_match');
        // $keys = $route_match->getParameter('keys');
        // dpm($keys);

        // dpm(urlencode("https://geeksforgeeks.org/"));

        $form['left']['what_is_bigcard_form'] = array(
        '#type'     =>'markup',
        '#markup'   =>'** what_is_bigcard_form',            
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
