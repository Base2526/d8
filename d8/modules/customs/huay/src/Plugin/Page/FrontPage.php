<?php

namespace Drupal\huay\Plugin\Page;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for page example routes.
 */
class FrontPage extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'front_page';
  }

  public function page() {
    $params = array();

    $block = [
      '#theme'  => 'front-page',
      '#cache'  => array("max-age" => 0),
      '#role'   => '',
      '#params' => $params,
    ];
    $block['#attached']['library'][] = 'huay/huay';

    return $block;
  }
}