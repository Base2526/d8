<?php

/**
 * @file
 * ModalExampleController class.
 */

namespace Drupal\modal_example\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Component\Serialization\Json;

class ModalExampleController extends ControllerBase {

  public function page() {
    $link_url = Url::fromRoute('modal_example.modal', ['js' => 'nojs']);

    $link_url->setOptions([
      'attributes' => [
        'class' => ['use-ajax', 'button', 'button--small'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode(['width' => 400]),
      ]
    ]);

    return array(
      '#type' => 'markup',
      '#markup' => Link::fromTextAndUrl(t('Open the modal'), $link_url)->toString(),
      '#attached' => ['library' => ['core/drupal.dialog.ajax']]
    );
  }

  public function modal($js = 'nojs') {
    if ($js == 'ajax') {
      $options = [
        'dialogClass' => 'popup-dialog-class',
        //'width' => '75%', // apply or override the width of the dialog
      ];
      $response = new AjaxResponse();
      $response->addCommand(new OpenModalDialogCommand(t('Modal title'), t('This is an example of a modal with Javascript.'), $options));
      return $response;
    } else {
      return t('This is an example of a fallback for a modal without Javascript.');
    }
  }
}
