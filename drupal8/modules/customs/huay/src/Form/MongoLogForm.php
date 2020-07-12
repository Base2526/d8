<?php

namespace Drupal\huay\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;

use Drupal\huay\Utils\Utils;

/**
 * Controller routines for page example routes.
 */
class MongoLogForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'mongo_log_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // dpm(Utils::fetch_mg_log());

        $form['refresh'] = array(
            '#type' => 'submit',
            '#name' => 'refresh',
            '#value' => t('Refresh'),
            '#submit' => ['::submitAjax'],
            '#ajax' => array(
                'callback' => '::callbackAjax',
                'wrapper' => 'container',
                'progress' => array(
                // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
                'type' => 'throbber',
                // Message to show along progress graphic. Default: 'Please wait...'.
                'message' => NULL,
                ),
            ),
        );

        $form['container'] = array(
            '#type' => 'container',
            '#prefix' => '<div id="container">',
            '#suffix' => '</div>',
        );

        $form['container']['txt'] = array(
            '#type' => 'item',
            '#markup' => Utils::fetch_mg_log(),
            '#prefix' => '<div>',
            '#suffix' => '</div>'
        );
        
        return $form;
    }

    public function submitAjax(array &$form, FormStateInterface $form_state) {
        $form_state->setRebuild();
    }

    public function callbackAjax(array &$form, FormStateInterface $form_state) {

        return $form['container'];
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