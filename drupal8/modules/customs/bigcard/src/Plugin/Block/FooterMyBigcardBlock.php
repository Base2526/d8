<?php

namespace Drupal\bigcard\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\bigcard\Utils\Utils;

/**
 * Provides a 'Bigcard' block.
 *
 * @Block(
 *   id = "footer_my_bigcard",
 *   admin_label = @Translation("Bigcard: Footer my bigcard")
 * )
 */
class FooterMyBigcardBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $footer_array = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('footer');
    // dpm($footer_array);
    $footer_tree = array();
    foreach($footer_array as $footer_key => $footer_item){
      // dpm($footer_item);

      $parent = $footer_item->parents[0];
      $name = $footer_item->name;
      $tid = $footer_item->tid;

      $link = '/' .\Drupal::languageManager()->getCurrentLanguage()->getId();
      if(!empty(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid)->get('field_link')->getValue())){
        $link = '/' .\Drupal::languageManager()->getCurrentLanguage()->getId() .\Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid)->get('field_link')->getValue()[0]['value'];
      }
      
      if($parent == '0'){
        // no parent
        $footer_tree[$tid] = array();
        $footer_tree[$tid]['name'] = $name;
        $footer_tree[$tid]['link'] = $link;
      }else{
        // has parent
        $footer_tree[$parent][$tid]['name'] = $name;
        $footer_tree[$parent][$tid]['link'] = $link;
      }
    }
    // dpm($footer_tree);

    $block = array();
    $block =[
              '#theme'     => 'footer-my-bigcard-block',
              '#params'    => array(
                'footer_tree' => $footer_tree
              ),
            ];

    $build['footer_my_bigcard'] = $block;
    return $build;
  }
}
