<?php

namespace Drupal\huay\Plugin\Page;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for page example routes.
 */
class LotteryPage extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'lottery_page';
  }

  public function page() {
    $params = array();

    $block = [
      '#theme'  => 'lottery-pageq',
      '#cache'  => array("max-age" => 0),
      '#role'   => '',
      '#params' => $params,
    ];
    $block['#attached']['library'][] = 'huay/huay';

    return $block;
  }
}