<?php

namespace Drupal\bigcard\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Bigcard' block.
 *
 * @Block(
 *   id = "menu_my_bigcard",
 *   admin_label = @Translation("Bigcard: Menu my bigcard")
 * )
 */
class MenuMyBigcardBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $side_bar_array = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('side_bar');
    // dpm($side_bar_array);
    $side_bar_obj = array();
    foreach($side_bar_array as $side_bar_key => $side_bar_item){
      // dpm($side_bar_item);

      $tid  = $side_bar_item->tid;
      $name = $side_bar_item->name;
      $icon = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid)->get('field_icon')->getValue()[0]['value'];
      $link = '/' .\Drupal::languageManager()->getCurrentLanguage()->getId() .\Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid)->get('field_link')->getValue()[0]['value'];

      // no parent
      $side_bar_obj[$tid] = array();
      $side_bar_obj[$tid]['name'] = $name;
      $side_bar_obj[$tid]['icon'] = $icon;
      $side_bar_obj[$tid]['link'] = $link;
    }
    // dpm($side_bar_obj);

   
    // $menu_name = 'menu-my-bigcard';
    // $menu_tree = \Drupal::menuTree();
    // $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
    // $parameters->setMinDepth(0);
    // //Delete comments to have only enabled links
    // //$parameters->onlyEnabledLinks();

    // $tree = $menu_tree->load($menu_name, $parameters);
    // $manipulators = array(
    //   array('callable' => 'menu.default_tree_manipulators:checkAccess'),
    //   array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
    // );
    // $tree = $menu_tree->transform($tree, $manipulators);
    // $params = [];

    // foreach ($tree as $item) {
     
    //   $title = $item->link->getTitle();
    //   $url = $item->link->getUrlObject()->toString();
    //   $params[] = array(  'title'=>$title, 
    //                       'url'=>$url
    //                     );//Link::fromTextAndUrl($title, $url);
    // }
    // // dpm($params);

    $block = array();
    $block =[
              '#theme'     => 'menu-my-bigcard-block',
              '#params'    => array(
                'side_bar_obj' => $side_bar_obj,
                'active' => '/' . \Drupal::languageManager()->getCurrentLanguage()->getId() . \Drupal::service('path.current')->getPath()
              ),
            ];
    // dpm(\Drupal::service('path.current')->getPath());

    $build['menu_my_bigcard_block'] = $block;
    return $build;
  }
}
