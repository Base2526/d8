<?php

namespace Drupal\huay\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;
// use Drupal\credit_sales_approval\Controller\MainPageController;

/**
 * Controller routines for page example routes.
 */
class RefreshForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'refresh_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['container1'] = array(
            '#type' => 'container',
            '#prefix' => '<div id="container1">',
            '#suffix' => '</div>',
        );

        dpm('55');

        $form['container1']['ex'] = array(
            '#type' => 'textfield',
            '#title' => t('Username'),
            '#attributes' => array('placeholder' => t('Username')),
            // '#description' => t('***Enter your Credit sales Approval username.'),
            // '#default_value' => $name,
            '#size' => 25,

            '#ajax' => [
                // 'callback' => [$this, 'AjaxTraiteReference'],
                'callback' => '::callbackAjax',
                'event' => 'change',
                'wrapper' => 'container',
                'progress' => [
                  'type' => 'throbber',
                  'message' => t(''),
                ],
              ],
        );
    
        return $form;
    }

    public function callbackAjax(array &$form, FormStateInterface $form_state) {
        dpm('callbackAjax');
        return $form['container1'];
    }

    /**
     * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
    }
}