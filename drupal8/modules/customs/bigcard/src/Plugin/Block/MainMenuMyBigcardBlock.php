<?php

namespace Drupal\bigcard\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;

use Drupal\Core\Url;

use Drupal\bigcard\Utils\Utils;

/**
 * Provides a 'Bigcard' block.
 *
 * @Block(
 *   id = "main_menu_my_bigcard_block",
 *   admin_label = @Translation("Bigcard: Main menu my bigcard block")
 * )
 */
class MainMenuMyBigcardBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block  = array();
    $params = array();

    $language =  \Drupal::languageManager()->getCurrentLanguage()->getId();
    $path_en = Utils::get_base_url()  . 'en';
    $path_th = Utils::get_base_url()  . 'th';
    if(!\Drupal::service('path.matcher')->isFrontPage()){
      $path_en .= \Drupal::service('path.current')->getPath();
      $path_th .= \Drupal::service('path.current')->getPath();
    }
    $params['language']['en'] = $path_en;
    $params['language']['th'] = $path_th; 

    $params['active'] = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $params['lang'] = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $params['is_authenticated'] = \Drupal::currentUser()->isAuthenticated();

    $block =[
              '#theme'     => 'main-menu-my-bigcard-block',
              '#params'    => $params,
            ];

    $build['main_menu_my_bigcard_block'] = $block;
    return $build;
  }
}
