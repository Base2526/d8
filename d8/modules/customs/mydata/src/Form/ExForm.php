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

        // $form['js_action'] = array(
        //     '#type' => 'button', 
        //     '#value' => t('Click me'), 
        // );
        // $form['js_action']['#attached'] = array(
        //     'js' => array(drupal_get_path('module', 'mydata') . '/mydata_key.js',)
        // );

        $form['actions']['chect-socket.io'] = [
            '#type' => 'button',
            '#value' => $this->t('Check socket.io'),
            '#attributes' => [
              'onclick' => 'return false;'
            ],
            // '#attached' => array(
            //   'library' => array(
            //     'mydata/mydata_key',
            //   ),
            // ),
        ];

        $form['actions']['create-room'] = [
            '#type' => 'button',
            '#value' => $this->t('Create room'),
            '#attributes' => [
              'onclick' => 'return false;'
            ],
            // '#attached' => array(
            //   'library' => array(
            //     'mydata/mydata_key',
            //   ),
            // ),
        ];

        $form['actions']['list-client'] = [
            '#type' => 'button',
            '#value' => $this->t('Get list of clients in specific room'),
            '#attributes' => [
              'onclick' => 'return false;'
            ],
            // '#attached' => array(
            //   'library' => array(
            //     'mydata/mydata_key',
            //   ),
            // ),
        ];

        $form['actions']['send-message-to-client'] = [
            '#type' => 'button',
            '#value' => $this->t('Send message to client'),
            '#attributes' => [
              'onclick' => 'return false;'
            ],
            // '#attached' => array(
            //   'library' => array(
            //     'mydata/mydata_key',
            //   ),
            // ),
        ];
        
        $form['actions']['#type'] = 'actions';
        $form['actions']['buttton_submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Send'),
            // '#button_type' => 'primary',
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