<?php

declare(strict_types = 1);

namespace Drupal\modal_form_example\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class SubscribeToGroupForm extends FormBase {

    public function getFormId(): string {
        return 'subscribe_to_group_form';
      }
    
      public function buildForm(array $form, FormStateInterface $form_state): array {
        $form['description'] = [
          '#type' => 'html_tag',
          '#tag' => 'p',
          '#value' => $this->t('Do you want to receive our weekly newsletter?'),
        ];
        $form['actions'] = [
          '#type' => 'actions',
        ];
        $form['actions']['cancel'] = [
          '#type' => 'submit',
          '#value' => $this->t('No thanks'),
          '#attributes' => [
            'class' => ['dialog-cancel'],
          ],
        ];
    
        // The trick to making it work lies here. We need to specify the callback,
        // the URL to the form route, and set AJAX_FORM_REQUEST in the query.
        $form['actions']['confirm'] = [
          '#type' => 'submit',
          '#value' => $this->t('Subscribe'),
          '#ajax' => [
            'callback' => [static::class, 'confirmSubscription'],
            'url' => Url::fromRoute('collection.subscribe_to_group_form'),
            'options' => [
              'query' => [
                FormBuilderInterface::AJAX_FORM_REQUEST => TRUE,
              ],
            ],
          ],
        ];
    
        return $form;
      }

      /**
         * {@inheritdoc}
         */
        public function submitForm(array &$form, FormStateInterface $form_state) {}

    
      public function confirmSubscription(array &$form, FormStateInterface $form_state): AjaxResponse {
        $response = new AjaxResponse();
    
        // Output messages in the page.
        $messages = ['#type' => 'status_messages'];
        $response->addCommand(new PrependCommand('.section--content-top', $messages));
    
        if (!$form_state->getErrors()) {
          $response->addCommand(new CloseModalDialogCommand());
        }
        return $response;
      }
}