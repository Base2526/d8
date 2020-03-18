<?php

declare(strict_types = 1);

namespace Drupal\modal_form_example\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JoinGroupForm extends FormBase {

    protected $formBuilder;

    public function __construct(FormBuilderInterface $form_builder) {
      $this->formBuilder = $form_builder;
    }
  
    public static function create(ContainerInterface $container): JoinGroupForm {
      return new static(
        $container->get('form_builder')
      );
    }
  
    public function getFormId(): string {
      return 'join_group_form';
    }
  
    public function buildForm(array $form, FormStateInterface $form_state): array {
    //   $membership = $this->getGroupMembership();
    //   if (empty($membership)) {
        $form['join'] = [
          '#ajax' => [
            'callback' => '::showSubscribeDialog',
          ],
          '#type' => 'submit',
          '#value' => $this->t('Join this group'),
        ];
    //   }
    //   else {
    //     $form['leave'] = [
    //       '#type' => 'link',
    //       '#title' => $this->t('Leave this group'),
    //       '#url' => Url::fromRoute('group.leave_form'),
    //     ];
    //   }
  
      return $form;
    }

    /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  
    public function showSubscribeDialog(array &$form, FormStateInterface $form_state): AjaxResponse {
      $response = new AjaxResponse();
  
      // Output messages in the page.
      $messages = ['#type' => 'status_messages'];
      $response->addCommand(new PrependCommand('.section--content-top', $messages));
  
      // If the form submitted successfully, make an offer the user cannot refuse.
      if (!$form_state->getErrors()) {
        // Rebuild the form and replace it in the page, so that the "Join this
        // "group" button will be replaced with the "Leave this group" link.
        $rebuilt_form = $this->formBuilder->rebuildForm('join_group_form', $form_state, $form);
        $response->addCommand(new ReplaceCommand('#join-group-form', $rebuilt_form));
  
        // Open the second form in a modal dialog.
        $title = $this->t('Welcome to the group!');
  
        $modal_form = $this->formBuilder->getForm('\Drupal\modal_form_example\Form\SubscribeToGroupForm');
        $modal_form['#attached']['library'][] = 'core/drupal.dialog.ajax';
  
        $response->addCommand(new OpenModalDialogCommand($title, $modal_form, ['width' => '500']));
      }
      return $response;
    }

}