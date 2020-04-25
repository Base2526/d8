<?php
namespace Drupal\huay\Utils;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
class Utils extends ControllerBase {
  public static function getTaxonomy_term($cid, $clear = FALSE){
    $type = 'taxonomy_term';

    $our_service = \Drupal::service('huay.cache');
    $cache = $our_service->getCache($type, $cid);
    if($cache  === NULL || $clear) {
      $branchs_terms = \Drupal::entityManager()->getStorage($type)->loadTree($cid);
      $branchs = array();

      // จะมีกรณีที่ tid เกิดไม่ต้องกันในเครือง dev, uat, production เราจึงกำหนด id ให่แต่ละ term เราจึงต้องดึงจาก field_id_term เราต้อง check เพราะว่าเราค่อยแก้ๆ
      // $terms = array('current_d_e_ratio', 'type_payment');

    //   $terms = \Drupal\config_pages\Entity\ConfigPages::config('vocabulary')->get('field_vocabulary')->value;
    //   $terms = explode(",", $terms);

    //   if (in_array( $cid , $terms)) {
      foreach($branchs_terms as $tag_term) {
        $tid_code = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag_term->tid)->get('field_tid_code')->getValue();
        if(!empty( $tid_code )){
          $new_tid =  $tid_code[0]['value'];
          $branchs[$new_tid] = $tag_term->name;
        }

        // $branchs[$tag_term->tid] = $tag_term->name;
      }
    //   }else{
    //     foreach ($branchs_terms as $tag_term) {
    //       $branchs[$tag_term->tid] = $tag_term->name;
    //     }
    //   }

      $our_service->setCache($type, $branchs, $cid);
      return $branchs;
    }else{
      return $cache;
    }   
  }

  // Utils::mail_send('local@local.lo', array(0=>'A', 1=>'B'));
  public function mail_send($to, $params){
    $mailManager = \Drupal::service('plugin.manager.mail');

    $module = 'printing_administrative_software';
    $key    = 'pas_email_test';
    // $to = 'android.somkid@gmail.com';
    // $params['theme']    = $theme;
    // $params['title']    = $params['title'];
    // $params['message']  = $params['message'];
    
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if ($result['result'] !== true) {
      drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
    }
    else {
      // drupal_set_message(t('Your message has been sent.'));
    }
  }

  public static function encode($string) {
    $key    = sha1(\Drupal\config_pages\Entity\ConfigPages::config('config_global')->get('field_key_ende')->value);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j      = 0;
    $hash   = '';
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string,$i,1));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return $hash;
  }

  public static  function decode($string) {
    $key    = sha1(\Drupal\config_pages\Entity\ConfigPages::config('config_global')->get('field_key_ende')->value);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j      = 0;
    $hash   = '';
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
  }

}
