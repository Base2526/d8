<?php
/**
 * @file
 * Contains \Drupal\mydata\Form\ExForm.
 */
namespace Drupal\mydata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ExForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'ex_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['list_message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('List message'),
            '#rows' => 10,
            '#attributes' => array('readonly' => 'readonly')
            // '#format' => 'full_html',
            // '#description' => $this->t('Message display to customer contacts.'),
            // '#default_value' => $config->get('page_message'),
        ];
        $form['message'] = array(
            '#type' => 'textfield',
            '#title' => t('Message:'),
            '#required' => TRUE,
        );
        
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Send'),
            '#button_type' => 'primary',
        );
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {

        // if (strlen($form_state->getValue('candidate_number')) < 10) {
        //     $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
        // }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // drupal_set_message($this->t('@can_name ,Your application is being submitted!', array('@can_name' => $form_state->getValue('candidate_name'))));
        foreach ($form_state->getValues() as $key => $value) {
            drupal_set_message($key . ': ' . $value);
        }
    }
}