<?php
namespace Drupal\huay\Utils;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use \Drupal\config_pages\Entity\ConfigPages;

use \MongoDB\Client;

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
    $key    = sha1(Utils::GetConfigGlobal()['key_ende']);
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
    $key    = sha1(Utils::GetConfigGlobal()['key_ende']);
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

  public static function GetConfigGlobal(){
    $type = 'config_global';
    $cid  = 'all';

    $our_service = \Drupal::service('huay.cache');
    $cache = $our_service->getCache($type, $cid);
    if($cache  === NULL) {
      $configs = array();

      $config_global = ConfigPages::config('config_global');
      $configs =array(
        'key_ende'            => $config_global->get('field_key_ende')->value,

        'taxonomy_term'       => $config_global->get('field_taxonomy_term')->value,
        'mongodb_url'         => $config_global->get('field_mongodb_url')->value,
        'mongodb_replicaset'  => $config_global->get('field_mongodb_replicaset')->value,
      );

      $our_service->setCache($type, $configs, $cid);
      return $configs;
    }else{
      return $cache;
    }
  }

  public function GetMongoDB(){
    return (new Client(Utils::GetConfigGlobal()['mongodb_url'], array("replicaSet" => Utils::GetConfigGlobal()['mongodb_replicaset'])))->huay; 
  }

  public function GetCookie($str){
    // $str = "SESS49960de5880e8c687434170f6476605b=vKZraSjZVEasMEXptUoKMFc2dPVF_t71tKg5O76qG58; pga4_Something is wrong=2faf1078-c16b-41a2-98cb-8e7c25f6c149!L4DEAiWZ24IzkCryUlWNpcWXznI=; PGADMIN_LANGUAGE=en; mongo-express=s%3Ahv7Qt-Pwxlfxuk05ejFVeQ3UewjeEOKQ.aiK%2FVH%2FdGSyBXydSDSWxo0PmwhJsLER2O3dPcNumYdM; io=gU2U4STdGQWW3jEZAAAB";
    $arr_str = explode(";", $str);
    foreach ($arr_str as $key => $value){
      $arr_value = explode("=", $value);

      if(count($arr_value) == 2){
        if(strpos($arr_value[0], 'SESS')!== false){
          return trim($arr_value[1]);
        }
      }
    }
    return FALSE;
  }

  public static function get_file_url($target_id){   
    $file = \Drupal\file\Entity\File::load($target_id);
    return  !empty($file) ? $file->url() : '';
  }
}
