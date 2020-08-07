<?php
namespace Drupal\huay\Utils;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use \Drupal\config_pages\Entity\ConfigPages;
use Drupal\user\Entity\User;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\file\Entity\File;

use \MongoDB\Client;

class Utils extends ControllerBase {

  // client_secret : คือ YUhWaGVRPT0= : base64_encode(base64_encode('huay'))
  public static function verify($request, $check = TRUE){
    // if($check){
    //   if (  strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 && 
    //         strcmp( $request->headers->get('client_secret'), 'YUhWaGVRPT0=' ) === 0 ) {
    //     return TRUE;
    //   }
    // }else{
      return TRUE;
    // }

    // return FALSE;
  }

  public static function get_taxonomy_term($cid, $clear = FALSE){
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
        // $tid_code = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag_term->tid)->get('field_tid_code')->getValue();
        // if(!empty( $tid_code )){
        //   $new_tid =  $tid_code[0]['value'];
        //   $branchs[$new_tid] = $tag_term->name;
        // }

        $branchs[$tag_term->tid] = $tag_term->name;
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

  public static function get_fid_cache($fid){
    $type = 'fid_cache';

    $our_service = \Drupal::service('huay.cache');
    $cache = $our_service->getCache($type, $fid);
    if($cache  === NULL) {
      $file =File::load($fid);    
      
      if(empty($file)){
        return NULL;
      }

      $contents = file_get_contents( $file->getFileUri() );

      $our_service->setCache($type, $contents, $fid);
      return $contents;

      /*
      $branchs_terms = \Drupal::entityManager()->getStorage($type)->loadTree($cid);
      $branchs = array();

      // จะมีกรณีที่ tid เกิดไม่ต้องกันในเครือง dev, uat, production เราจึงกำหนด id ให่แต่ละ term เราจึงต้องดึงจาก field_id_term เราต้อง check เพราะว่าเราค่อยแก้ๆ
      // $terms = array('current_d_e_ratio', 'type_payment');

      //   $terms = \Drupal\config_pages\Entity\ConfigPages::config('vocabulary')->get('field_vocabulary')->value;
      //   $terms = explode(",", $terms);

      //   if (in_array( $cid , $terms)) {
      foreach($branchs_terms as $tag_term) {
        // $tid_code = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag_term->tid)->get('field_tid_code')->getValue();
        // if(!empty( $tid_code )){
        //   $new_tid =  $tid_code[0]['value'];
        //   $branchs[$new_tid] = $tag_term->name;
        // }

        $branchs[$tag_term->tid] = $tag_term->name;
      }
    //   }else{
    //     foreach ($branchs_terms as $tag_term) {
    //       $branchs[$tag_term->tid] = $tag_term->name;
    //     }
    //   }

      $our_service->setCache($type, $branchs, $cid);
      return $branchs;
      */

      // $file = file_get_contents('sites/default/files/shoot_numbers/41_07-27-2020_0830am.txt');
      // foreach( json_decode( $file ) as $i => $item) {
      //   dpm( json_decode($item) );
      // }
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

  public static function GetMongoDB(){
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

  public static function credit_balance($uid){
    /*
    // ยอดฝากเงินทั้งหมด
    $amount_of_money = 0;
    foreach ($user->get('field_deposit')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );
            
        $deposit_status = $p->get('field_deposit_status')->target_id;
        if($deposit_status == 15){
            // จำนวนเงินที่โอน
            $field_amount_of_money = $p->get('field_amount_of_money')->getValue();
            if(!empty($field_amount_of_money)){
                $amount_of_money += $field_amount_of_money[0]['value'];
            }
        }
    }

    //  ถอนเงิน field_withdraw
    $withdraw = 0;
    foreach ($user->get('field_withdraw')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );
        $deposit_status = $p->get('field_withdraw_status')->target_id;
        // เอาทุกสถานะ ยกเว้น สถานะไม่อนุมัติเท่านั้น
        if($deposit_status != 17){
            // จำนวนเงินที่โอน
            $field_amount_of_withdraw = $p->get('field_amount_of_withdraw')->getValue();
            if(!empty($field_amount_of_withdraw)){
                $withdraw += $field_amount_of_withdraw[0]['value'];
            }
        }
    }
    */

    // -------  ยอดฝากเงินทั้งหมด  --------
    $amount_of_money = 0;

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'user_deposit');
    $query->condition('status', 1);
    $query->condition('uid', $uid);
    $nids = $query->execute();
    if(!empty($nids)){
      $nodes = Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        if(strcmp($node->field_deposit_status->target_id, 15) == 0){
          $amount_of_money  += $node->field_amount->value;
        } 
      }
    }
    // -------  ยอดฝากเงินทั้งหมด  --------

    // ------- ถอนเงิน -------
    $withdraw = 0;
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'user_withdraw');
    $query->condition('status', 1);
    $query->condition('uid', $uid);
    $nids = $query->execute();
    if(!empty($nids)){
      $nodes = Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        if(strcmp($node->field_deposit_status->target_id, 15) == 0){
          $withdraw  += $node->field_amount_of_withdraw->value;
        } 
      }
    }
    // ------- ถอนเงิน -------

    return $amount_of_money - $withdraw;   
  }

  public static function get_user_deposit($uid){
    if(empty($uid)){
      return [];
    }

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'user_deposit');
    $query->condition('status', 1);
    $query->condition('uid', $uid);
    $nids = $query->execute();

    $deposits = array();
    if(!empty($nids)){
      $nodes = Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        $id               = $node->id();
        $amount           = $node->field_amount->value;
        $date_transfer    = $node->field_date_transfer->value;

        $deposit_status   = /*Utils::get_taxonomy_term('deposit_status')[*/ $node->field_deposit_status->target_id /*]*/;
        $transfer_method  = /*Utils::get_taxonomy_term('transfer_method')[*/ $node->field_transfer_method->target_id /*]*/;
        $huay_list_bank   = /*Utils::get_taxonomy_term('huay_list_bank')[*/ $node->field_huay_list_bank->target_id /*]*/;
        $list_bank        = /*Utils::get_taxonomy_term('list_bank')[*/ $node->field_list_bank->target_id /*]*/;

        $note             = empty($node->body->value) ? '' : strip_tags($node->body->value);
        $attached_file    = empty($node->field_attached_file) ? '' : Utils::get_file_url($node->field_attached_file->target_id);

        $create           = $node->getCreatedTime();
        $update           = $node->getChangedTime();

        $deposits[]       = array('id'            => $id,
                                  'deposit_status'=> $deposit_status,
                                  'amount'        => $amount,
                                  'transfer_method'=> $transfer_method,
                                  'huay_list_bank' => $huay_list_bank,
                                  'list_bank'      => $list_bank,
                                  'date_transfer'  => strval(strtotime($date_transfer)),
                                  'note'           => $note,
                                  'attached_file'  => $attached_file,
                                
                                  'create'         => $create,
                                  'update'         => $update);
      }  
    }
      
    return $deposits;
  }

  public static function get_user_withdraw($uid){
    if(empty($uid)){
      return [];
    }

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'user_withdraw');
    $query->condition('status', 1);
    $query->condition('uid', $uid);
    $nids = $query->execute();

    $deposits = array();
    if(!empty($nids)){
      $nodes = Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        $id               = $node->id();
        $amount           = $node->field_amount_of_withdraw->value;
        $deposit_status   = /*Utils::get_taxonomy_term('deposit_status')[*/ $node->field_deposit_status->target_id /*]*/;
        $user_id_bank     = /*Utils::get_taxonomy_term('transfer_method')[*/ $node->field_user_id_bank->value /*]*/;
        $note             = empty($node->body->value) ? '' : strip_tags($node->body->value);
       
        $create           = $node->getCreatedTime();
        $update           = $node->getChangedTime();

        $deposits[]       = array('id'            => $id,
                                  'deposit_status'=> $deposit_status,
                                  'amount'        => $amount,
                                  'user_id_bank'  => $user_id_bank,
                                  'note'           => $note,
                                
                                  'create'         => $create,
                                  'update'         => $update);
      }  
    }
      
    return $deposits;
  }

  public static function mongodb_people($uid){
    if(empty($uid)){
      return FALSE;
    }

    try {
      // เราต้องแปลงเป้น string เพราะว่า เวลาเรา mongo filter จะมอง int กับ string ไม่เท่ากัน
      $uid = is_int($uid) ? (string)$uid : $uid;

      $data = array();
      $user = User::load($uid);

      $image_url = '';  
      $user_picture = $user->get('user_picture')->getValue();
      if (!empty($user_picture)) {
          $image_url = Utils::get_file_url($user_picture[0]['target_id']);
      }

      $data = array(
                  'uid'      =>$uid,
                  'name'     =>$user->getUsername(),
                  'email'    =>$user->getEmail(),
                  'roles'    =>$user->getRoles(),
                  'image_url'=>$image_url,
                  'credit_balance' =>Utils::credit_balance($uid),
              );

      $banks = array();
      foreach ($user->get('field_bank')->getValue() as $bi=>$bv){
          $p = Paragraph::load( $bv['target_id'] );

          // ชื่อบัญชี
          $name = '';
          $field_name_bank = $p->get('field_name_bank')->getValue();
          if(!empty($field_name_bank)){
              $name = $field_name_bank[0]['value'];
          }

          // เลขที่บัญชี
          $number_bank = '';
          $field_number_bank = $p->get('field_number_bank')->getValue();
          if(!empty($field_number_bank)){
              $number_bank = $field_number_bank[0]['value'];
          }

          // ธนาคาร tid
          $bank_tid        = $p->get('field_bank')->target_id;

          // ชื่อธนาคาร
          $term = Term::load($bank_tid);
          $bank_name = $term->getName();

          
          

          $banks[$bv['target_id']] = array(
                                          'name'          =>$name,
                                          'number_bank'   =>$number_bank,
                                          'bank_tid'      =>$bank_tid,
                                          'bank_name'     =>$bank_name
                                          );
      }
      $data['banks'] = $banks;

      // $user_access = array();
      // foreach ($user->get('field_user_access')->getValue() as $bi=>$bv){
      //     $p = Paragraph::load( $bv['target_id'] );

      //     // cookie
      //     $cookie = '';
      //     $field_cookie = $p->get('field_cookie')->getValue();
      //     if(!empty($field_cookie)){
      //         $cookie = $field_cookie[0]['value'];
      //     }

      //     // field_socket_id
      //     $socket_id = '';
      //     $field_socket_id = $p->get('field_socket_id')->getValue();
      //     if(!empty($field_socket_id)){
      //         $socket_id = $field_socket_id[0]['value'];
      //     }
          
      //     $user_access[$bv['target_id']] = array('cookie' =>$cookie, 'socket_id' =>$socket_id);
      // }

      // $data['user_access'] = $user_access;

      // ฝากเงิน field_deposit
      // $deposit = array();
      // foreach ($user->get('field_deposit')->getValue() as $bi=>$bv){
      //     $p = Paragraph::load( $bv['target_id'] );

      //     // ID ธนาคารของเว็บฯ 
      //     $hauy_id_bank = '';
      //     $field_hauy_id_bank = $p->get('field_hauy_id_bank')->getValue();
      //     if(!empty($field_hauy_id_bank)){
      //         $hauy_id_bank = $field_hauy_id_bank[0]['value'];
      //     }

      //     // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า 
      //     $user_id_bank = '';
      //     $field_user_id_bank = $p->get('field_user_id_bank')->getValue();
      //     if(!empty($field_user_id_bank)){
      //         $user_id_bank = $field_user_id_bank[0]['value'];
      //     }

      //     // จำนวนเงินที่โอน
      //     $amount_of_money = '';
      //     $field_amount_of_money = $p->get('field_amount_of_money')->getValue();
      //     if(!empty($field_amount_of_money)){
      //         $amount_of_money = $field_amount_of_money[0]['value'];
      //     }

      //     // ช่องทางการโอนเงิน 
      //     $transfer_method = $p->get('field_transfer_method')->target_id;
        
      //     // วัน & เวลา ที่โอน 
      //     $date_transfer = '';
      //     $field_date_transfer = $p->get('field_date_transfer')->getValue();
      //     if(!empty($field_date_transfer)){
      //         $date_transfer = $field_date_transfer[0]['value'];
      //     }

      //     // หมายเหตุ 
      //     $annotation = '';
      //     $field_annotation = $p->get('field_annotation')->getValue();
      //     if(!empty($field_annotation)){
      //         $annotation = $field_annotation[0]['value'];
      //     }

      //     // สถานะการฝากเงิน 
      //     $deposit_status = $p->get('field_deposit_status')->target_id;

      //     $deposit[$bv['target_id']] = array( 'hauy_id_bank'      =>$hauy_id_bank, 
      //                                         'user_id_bank'      =>$user_id_bank,
      //                                         'amount_of_money'   =>$amount_of_money,
      //                                         'transfer_method'   =>$transfer_method,
      //                                         'date_transfer'     =>$date_transfer,
      //                                         'annotation'        =>$annotation,
      //                                         'deposit_status'    =>$deposit_status,
      //                                         );
      // }
      $data['deposit'] = Utils::get_user_deposit($uid);

      // ถอนเงิน 
      // $withdraw = array();
      // foreach ($user->get('field_withdraw')->getValue() as $bi=>$bv){
      //     $p = Paragraph::load( $bv['target_id'] );

      //     // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า 
      //     $user_id_bank = '';
      //     $field_user_id_bank = $p->get('field_user_id_bank')->getValue();
      //     if(!empty($field_user_id_bank)){
      //         $user_id_bank = $field_user_id_bank[0]['value'];
      //     }

      //     // จำนวนเงินที่ถอน
      //     $amount_of_withdraw = '';
      //     $field_amount_of_withdraw = $p->get('field_amount_of_withdraw')->getValue();
      //     if(!empty($field_amount_of_withdraw)){
      //         $amount_of_withdraw = $field_amount_of_withdraw[0]['value'];
      //     }

      //     // หมายเหตุ 
      //     $annotation = '';
      //     $field_annotation = $p->get('field_annotation')->getValue();
      //     if(!empty($field_annotation)){
      //         $annotation = $field_annotation[0]['value'];
      //     }

      //     // สถานะการฝากเงิน 
      //     $withdraw_status = $p->get('field_withdraw_status')->target_id;

      //     $withdraw[$bv['target_id']] = array('user_id_bank'      =>$user_id_bank,
      //                                         'amount_of_withdraw'=>$amount_of_withdraw,
      //                                         'annotation'        =>$annotation,
      //                                         'withdraw_status'   =>$withdraw_status,
      //                                         );
      // }
      $data['withdraw'] = Utils::get_user_withdraw($uid);;

      // รายการโพยหวย ( ส่วนของลูกค้า ) 
      $chits = array();
      foreach ($user->get('field_chits')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );

        if(!empty($p)){
          $field_chit_id = $p->get('field_chit_id')->getValue();
          if(!empty($field_chit_id)){
            $chit_id = $field_chit_id[0]['value'];
    
            $node = Node::load($chit_id);
            if(!empty($node)){
                $name = $node->label();
    
                // สถานะโพย รอดำเนินการ/ยกเลิก/อนุมัติ
                $chit_status_name = '';
                $chit_status_id  = $node->get('field_chit_status')->target_id;
                if(empty($chit_status_id)){
                  continue;
                }
                $chit_status_term = Term::load($chit_status_id);
                $chit_status_name = $chit_status_term->getName();
                
                // ประเภทโพยหวย (หวยเด่น & หวยหุ้น) หวยรัฐบาลไทย/ยี้กี่/หวยลาว
                $chit_type_name = '';
                $type_id    = $node->get('field_chit_type')->target_id;
                if(empty($type_id)){
                  continue;
                }
                $chit_type_term = Term::load($type_id);
                $chit_type_name = $chit_type_term->getName();
                
                $round_name = '';
                $round_id =0;
                if($type_id == 67){
                    // ยี่กี่ 
                    // ถ้าเป็น ยี่กี่เราต้องดึงรอบมาด้วย
                    $round_id = $node->get('field_yeekee_round')->target_id;
                    // if(empty($round_id)){
                    //   continue;
                    // }
                    $round_name = Term::load($round_id)->getName();
                }
    
                // หมายเหตุ
                $note =''; 
                $field_note = $node->get('field_note')->getValue();
                if(!empty($field_note)){
                    $note = $field_note[0]['value'];
                } 
    
                $list_bet = array();
                // รายการหวยทั้งหมด field_list_bet
                foreach ($node->get('field_list_bet')->getValue() as $bi=>$bv){
                    $p = Paragraph::load( $bv['target_id'] );
                    // dpm($bv['target_id']);
    
                    // ประเภทโหมด ประเภท สามตัวบน สามตัวโต๊ด
                    $mode_target_id = $p->get('field_bet_type')->target_id;
                    
                    $bet_item = array();
                    // รายการของแต่ละโหมด
                    foreach ($p->get('field_bet_item')->getValue() as $mi=>$mv){
                        // dpm($mv['target_id']);
    
                        $mp = Paragraph::load( $mv['target_id'] );
    
                        // ราคา
                        $price = 0; 
                        $field_item_chit_price = $mp->get('field_item_chit_price')->getValue();
                        if(!empty($field_item_chit_price)){
                            $price = $field_item_chit_price[0]['value'];
                        } 
    
                        // เลข
                        $number = 0;
                        $field_item_chit_number = $mp->get('field_item_chit_number')->getValue();
                        if(!empty($field_item_chit_number)){
                            $number = $field_item_chit_number[0]['value'];
                        } 
                        
                        // dpm($price, $number);
    
                        $bet_item[] = array('pid'    => $mv['target_id'],  
                                            'number' => $number, 
                                            'price'  => $price );
                    }
    
                    $list_bet[] = array('pid'           => $bv['target_id'],  
                                        'mode'          => $mode_target_id, 
                                        'bet_item'      => $bet_item);
                }
    
                $chits[] = array( 'nid'       => $chit_id,
                                  'name'      => $name,
    
                                  'type_id'   =>$type_id,
                                  
                                  'chit_status_id'    => $chit_status_id,
                                  'chit_status_name'  => $chit_status_name,
                                  'chit_type_name'    => $chit_type_name,
                                  'round_name'        => $round_name,
                                  'round_id'          =>$round_id,
                                  'note'              => $note,
                                  'changed'           => $node->getChangedTime(),
                                  'list_bet'          => $list_bet);
            } 
          }
        }
      }
      $data['chits'] = $chits;

      // รายการโพยหวย ( ส่วนของเจ้ามือ )
      $dealers = array();
      foreach ($user->get('field_list_lotterys')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );
        if(!empty($p)){
          $field_chit_id = $p->get('field_chit_id')->getValue();
          if(!empty($field_chit_id)){
            $chit_id = $field_chit_id[0]['value'];
    
            $node = Node::load($chit_id);
            if(!empty($node)){
                $name = $node->label();
    
                // สถานะโพย
                $status_id    = $node->get('field_chit_status')->target_id;
    
                // ประเภทโพยหวย (หวยเด่น & หวยหุ้น)
                $type_id    = $node->get('field_chit_type')->target_id;
    
                $round_id =0;
                if($type_id == 67){
                    // ยี่กี่ 
                    // ถ้าเป็น ยี่กี่เราต้องดึงรอบมาด้วย
                    $round_id = $node->get('field_yeekee_round')->target_id;
                }
    
                // หมายเหตุ
                $note =''; 
                $field_note = $node->get('field_note')->getValue();
                if(!empty($field_note)){
                    $note = $field_note[0]['value'];
                } 
    
                $list_bet = array();
                // รายการหวยทั้งหมด field_list_bet
                foreach ($node->get('field_list_bet')->getValue() as $bi=>$bv){
                    $p = Paragraph::load( $bv['target_id'] );
                    // dpm($bv['target_id']);
    
                    // ประเภทโหมด ประเภท สามตัวบน สามตัวโต๊ด
                    $mode_target_id = $p->get('field_bet_type')->target_id;
                    
                    $bet_item = array();
                    // รายการของแต่ละโหมด
                    foreach ($p->get('field_bet_item')->getValue() as $mi=>$mv){
                        // dpm($mv['target_id']);
    
                        $mp = Paragraph::load( $mv['target_id'] );
    
                        // ราคา
                        $price = 0; 
                        $field_item_chit_price = $mp->get('field_item_chit_price')->getValue();
                        if(!empty($field_item_chit_price)){
                            $price = $field_item_chit_price[0]['value'];
                        } 
    
                        // เลข
                        $number = 0;
                        $field_item_chit_number = $mp->get('field_item_chit_number')->getValue();
                        if(!empty($field_item_chit_number)){
                            $number = $field_item_chit_number[0]['value'];
                        } 
                        
                        // dpm($price, $number);
    
                        $bet_item[] = array('pid'    => $mv['target_id'],  
                                            'number' => $number, 
                                            'price'  => $price );
                    }
    
                    $list_bet[] = array('pid'           => $bv['target_id'],  
                                        'mode'          => $mode_target_id, 
                                        'bet_item'      => $bet_item);
                }
    
                $dealers[] = array( 'nid'       => $chit_id,
                                    'name'      => $name,
                                    'status_id' => $status_id,
                                    'type_id'   => $type_id,
                                    'round_id'  => $round_id,
                                    'note'      => $note,
                                    'list_bet'  => $list_bet);
            } 
          }
        }
      }
      $data['dealers'] = $dealers;

      $collection = Utils::GetMongoDB()->people;
      $filter = array('uid'=>$uid);

      // \Drupal::logger('huay')->notice(serialize($filter));
      if($collection->count($filter)){
          // udpate
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->updateOne($filter, array('$set' =>$data) );
      }else{
          // create
          $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->insertOne($data);
      }
      return TRUE;
    }
    catch (Exception $e) {
      // echo $e->getMessage();
      \Drupal::logger('huay')
          ->notice('mongodb_people %error .', array(
          '%error' => $e->getMessage(),
        ));
      return FALSE;
    }
  }

  // public static function mongodb_fetch_lotterys(){
  //   // เราควรจะทำ file cache ระบบไว้ด้วย เพือไม่ต้องวิง่ไปดึง mongodb every time.
  //   $collection = Utils::GetMongoDB()->lotterys;
  //   $cursor = $collection->find();
  //   $datas = array();
  //   foreach ( $cursor as $id => $value ){
  //       $value = $value->jsonSerialize();
  //       $data = array();
  //       $data['_id']          = $value->_id->__toString();
  //       $data['tid']          = $value->tid;
  //       $data['name']         = $value->name;
  //       $data['end_time']     = $value->end_time;
  //       $data['is_open']      = $value->is_open;
  //       $data['image_url']    = $value->image_url;
  //       $data['type_lottery'] = $value->type_lottery;
  //       $data['weight']       = $value->weight;
  //       $data['createdAt']    = $value->createdAt->toDateTime()->format(\DateTimeInterface::W3C);
  //       $data['updatedAt']    = $value->updatedAt->toDateTime()->format(\DateTimeInterface::W3C);
  //       if(isset($value->rounds)){
  //         $rounds = array();
  //         foreach ( $value->rounds as $round_id => $round_value ){
  //           $round_value = $round_value->jsonSerialize();
  //           $rounds[] = array('tid'     =>$round_value->tid, 
  //                             'name'    =>$round_value->name, 
  //                             'end_time'=>$round_value->end_time, 
  //                             'weight'  =>$round_value->weight);
  //         }
  //         $data['rounds'] = $rounds;
  //       }else{
  //         $data['rounds'] = array();
  //       }
  //       $datas[] = $data;
  //   }
  //   return $datas;
  // }

  public static function huay_init(){
    $query = \Drupal::entityQuery('user');
    $uids = $query->condition('uid', array(0), 'NOT IN')->execute();
    foreach ($uids as $i=>$uid){
      Utils::mongodb_people($uid);
    }

    Utils::mongodb_contact_us(null);
    Utils::mongodb_transfer_method(null);
    Utils::mongodb_huay_list_bank(null);
    Utils::mongodb_list_bank(null);
    Utils::mongodb_lotterys(null);


    // $collection = (new MongoDB\Client)->test->restaurants;

    // $collection = Utils::GetMongoDB()->shoot_numbers;
    // ลบ document all
    // $result = $collection->deleteMany([]);
    // dpm($result);
  }

  // Contact us
  public function mongodb_contact_us($id){
    $contact_us = ConfigPages::config('contact_us');

    $data = array(
        'id'            => $id,
        'line_at'       => $contact_us->get('field_contact_us_line_at')->value,
        'url_qrcode'    => Utils::get_file_url($contact_us->get('field_contact_us_qrcode')->getValue()[0]['target_id']),
        'description'   => $contact_us->get('field_contact_us_description')->value,
        'tel'           => $contact_us->get('field_contact_us_tel')->value,
    );

    $collection = Utils::GetMongoDB()->contact_us;
    $filter = array('id'=>$id);
    if($collection->count($filter)){
        // udpate
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $collection->updateOne($filter, array('$set' =>$data) );
    }else{
        // create
        $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $collection->insertOne($data);
    }
  }

  // ช่องทางการโอนเงิน
  public function mongodb_transfer_method($tid){
    // if(empty($tid)){
    //     return FALSE;
    // }

    $type = 'taxonomy_term';
    $cid = 'transfer_method';
    $branchs_terms = \Drupal::entityManager()->getStorage($type)->loadTree($cid);

    $collection = Utils::GetMongoDB()->transfer_method;
    foreach($branchs_terms as $tag_term) {
        $data = array();
        $data['tid']    = $tag_term->tid;
        $data['name']   = $tag_term->name;

        $filter = array('tid'=>$tag_term->tid);
        if($collection->count($filter)){
            $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
            $collection->updateOne($filter, array('$set' =>$data) );
        }else{
            // create
            $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
            $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
            $collection->insertOne($data);
        }
    }
  }

  // รายชื่อธนาคาร ของเว็บฯ
  public function mongodb_huay_list_bank($tid){
    // if(empty($tid)){
    //     return FALSE;
    // }

    $type = 'taxonomy_term';
    $cid = 'huay_list_bank';
    $branchs_terms = \Drupal::entityManager()->getStorage($type)->loadTree($cid);

    $collection = Utils::GetMongoDB()->huay_list_bank;
    foreach($branchs_terms as $tag_term) {
      $data = array();
      $data['tid']    = $tag_term->tid;
      $data['name']   = $tag_term->name;

      $load = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag_term->tid);

      $huay_name_bank = '';
      $field_huay_name_bank = $load->get('field_huay_name_bank')->getValue();
      if(!empty($field_huay_name_bank)){
          $huay_name_bank = $field_huay_name_bank[0]['value'];
      }

      $huay_number_bank = '';
      $field_huay_number_bank = $load->get('field_huay_number_bank')->getValue();
      if(!empty($field_huay_number_bank)){
          $huay_number_bank = $field_huay_number_bank[0]['value'];
      }
      $data['huay_name_bank']    = $huay_name_bank;
      $data['huay_number_bank']   = $huay_number_bank;

      $filter = array('tid'=>$tag_term->tid);
      if($collection->count($filter)){
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->updateOne($filter, array('$set' =>$data) );
      }else{
          // create
          $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->insertOne($data);
      }
    }
  }

  // รายชื่อธนาคาร
  public function mongodb_list_bank($tid){
    // if(empty($tid)){
    //     return FALSE;
    // }

    $type = 'taxonomy_term';
    $cid  = 'list_bank';
    $branchs_terms = \Drupal::entityManager()->getStorage($type)->loadTree($cid);

    $collection = Utils::GetMongoDB()->list_bank;
    foreach($branchs_terms as $tag_term) {
      $data = array();
      $data['tid']    = $tag_term->tid;
      $data['name']   = $tag_term->name;
      $data['weight'] = $tag_term->weight;

      $filter = array('tid'=>$tag_term->tid);
      if($collection->count($filter)){
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->updateOne($filter, array('$set' =>$data) );
      }else{
          // create
          $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->insertOne($data);
      }
    }
  }

  // ประเภทหวย
  public function mongodb_lotterys($tid){
    // if(empty($tid)){
    //     return FALSE;
    // }

    $type = 'taxonomy_term';
    $cid  = 'lotterys';
    $branchs_terms = \Drupal::entityManager()->getStorage($type)->loadTree($cid);

    $collection = Utils::GetMongoDB()->lotterys;
    foreach($branchs_terms as $tag_term) {

        if($tag_term->tid == 67){

          Utils::mongodb_lotterys_yeekee_rounds();
          continue;
        }

        $child_term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag_term->tid);
   
        // $huay_name_bank = '';
        // $field_huay_name_bank = $load->get('field_huay_name_bank')->getValue();
        // if(!empty($field_huay_name_bank)){
        //     $huay_name_bank = $field_huay_name_bank[0]['value'];
        // }

        $data = array();
        // $end_time = 0;
        // $field_end_time = $child_term->get('field_end_time')->getValue();
        // if(!empty($field_end_time)){
        //   $end_time = $field_end_time[0]['value'];
        // }

        // field_open
        $is_open = FALSE;
        $field_open = $child_term->get('field_open')->getValue();
        if(!empty($field_open)){
          if ($field_open[0]['value']) {
            $is_open = TRUE;
          }
        }

        $is_display     = FALSE;
        $field_display  = $child_term->get('field_display')->getValue();
        if(!empty($field_display)){
          if ($field_display[0]['value']) {
            $is_display = TRUE;
          }
        }

        $image_url = '';
        $field_image = $child_term->get('field_image')->getValue();
        if(!empty($field_image)){
            $image_url = Utils::get_file_url($field_image[0]['target_id']);
        }

        $type_lottery = $child_term->get('field_type_lottery')->target_id;

        $data['tid']       = $tag_term->tid;
        $data['name']      = $tag_term->name;;
        // $data['end_time']  = $end_time;
        $data['is_open']   = $is_open;
        $data['is_display']= $is_display;
        $data['image_url'] = $image_url;
        $data['weight']    = $tag_term->weight;
        $data['type_lottery']= $type_lottery;

        // หวยรัฐบาลไทย
        if($tag_term->tid == 66){
          // $date = '';
          $data['date'] = '';
          $field_date = $child_term->get('field_date')->date;
          if(!empty($field_date)){
            // $date = $field_date[0]['value'];
            $data['date']= $field_date->getTimestamp() * 1000;
          }
        }

        // จับยี่กี VIP
        
        if($tag_term->tid == 67){
          $type = 'taxonomy_term';
          $yeekee_round_terms = \Drupal::entityManager()->getStorage($type)->loadTree('yeekee_round');

          $rounds = array();
          foreach($yeekee_round_terms as $yeekee_round_tag_term) {
              $round = array();
              $round['tid']    = $yeekee_round_tag_term->tid;
              $round['name']   = $yeekee_round_tag_term->name;
              

              // $yeekee_round_tag = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($yeekee_round_tag_term->tid);
              // $field_end_time = $yeekee_round_tag->get('field_end_time')->getValue();
              // if(!empty($field_end_time)){
              //   $round['end_time'] = $field_end_time[0]['value'];
              // }else{
              //   $round['end_time'] = 0;
              // }
              // $round['end_time'] = 0;
              // $round['date'] = '';
              // $field_yk_round = $yeekee_round_tag->get('field_yk_round')->date;
              // if(!empty($field_yk_round)){
              //   $round['date']= $field_yk_round->getTimestamp() * 1000;
              // }

              $weight = $yeekee_round_tag_term->weight;

              // $date = new \DateTime();
              // $date->setTime(6, 15*$weight, 0);

              $term = Term::load($yeekee_round_tag_term->tid);

              $date = new \DateTime();
              $date->setTimestamp($term->field_time_answer->value);

              if( (new \DateTime())->getTimestamp() > $date->getTimestamp() ){
                $round['is_close'] = TRUE;
              }else{
                $round['is_close'] = FALSE;
              }

              
              // $r = 15*($ytag_term->name - 1);
              // $date->setTime(6, 10, 0);

              $round['date']   = $date->getTimestamp() * 1000;
              $round['weight'] = $weight;

              $rounds[] = $round;
          }
          $data['rounds'] = $rounds;
        }
        

        $filter = array('tid'=>$tag_term->tid);
        if($collection->count($filter)){
            $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
            $collection->updateOne($filter, array('$set' =>$data) );
        }else{
            // create
            $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
            $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
            $collection->insertOne($data);
        }
    }
  }

  /*
  เราแยกรอบ ยี่กี่ออกมาเพราะ ยี่กี่จะมีการ update ทุกวัน
  */
  public function mongodb_lotterys_yeekee_rounds(){
    $data = array();

    $child_term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load(67);

    // field_open
    $is_open = FALSE;
    $field_open = $child_term->get('field_open')->getValue();
    if(!empty($field_open)){
        if ($field_open[0]['value']) {
        $is_open = TRUE;
        }
    }

    $is_display     = FALSE;
    $field_display  = $child_term->get('field_display')->getValue();
    if(!empty($field_display)){
        if ($field_display[0]['value']) {
        $is_display = TRUE;
        }
    }

    $image_url = '';
    $field_image = $child_term->get('field_image')->getValue();
    if(!empty($field_image)){
        $image_url = Utils::get_file_url($field_image[0]['target_id']);
    }

    $type_lottery = $child_term->get('field_type_lottery')->target_id;

    $data['tid']       = '67';//$tag_term->tid;
    $data['name']      = $child_term->label();
    // $data['end_time']  = $end_time;
    $data['is_open']   = $is_open;
    $data['is_display']= $is_display;
    $data['image_url'] = $image_url;
    $data['weight']    = $child_term->getWeight();
    $data['type_lottery']= $type_lottery;

    // จับยี่กี VIP
    // if($tag_term->tid == 67){
    $type = 'taxonomy_term';
    $yeekee_round_terms = \Drupal::entityManager()->getStorage($type)->loadTree('yeekee_round');

    $rounds = array();
    foreach($yeekee_round_terms as $yeekee_round_tag_term) {
        $round = array();
        $round['tid']    = $yeekee_round_tag_term->tid;
        $round['name']   = $yeekee_round_tag_term->name;
        
        $weight = $yeekee_round_tag_term->weight;

        $term = Term::load($yeekee_round_tag_term->tid);

        $date = new \DateTime();
        $date->setTimestamp($term->field_time_answer->value);

        if( (new \DateTime())->getTimestamp() > $date->getTimestamp() ){
        $round['is_close'] = TRUE;
        }else{
        $round['is_close'] = FALSE;
        }

        $round['date']   = $date->getTimestamp() * 1000;
        $round['weight'] = $weight;

        $rounds[] = $round;
    }
    $data['rounds'] = $rounds;
    // }

    $collection = Utils::GetMongoDB()->lotterys;
    $filter = array('tid'=>'67');
    if($collection->count($filter)){
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $collection->updateOne($filter, array('$set' =>$data) );
    }else{
        // create
        $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $collection->insertOne($data);
    }
  }

  public static function mongodb_shoot_number($tid){
    if(empty($tid)){
        return FALSE;
    }

    $collection = Utils::GetMongoDB()->shoot_numbers;
    $node = Node::load($tid);
    if(!empty($node)){
      $round_id    = $node->get('field_yeekee_round')->target_id;

      $end_time = '';
      $round = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($round_id);
      $field_end_time = $round->get('field_end_time')->getValue();
      if(!empty($field_end_time)){
          $field_end_time = explode(".", $field_end_time[0]['value']);
          $dt = new \DateTime();

          $round_name = $round->get('name')->getValue()[0]['value'];
          if($round_name > 72){
              $dt->modify('+1 day');
          }
          
          $dt->setTime($field_end_time[0], $field_end_time[1]);
          $end_time = $dt->getTimestamp();
      }

      $query = \Drupal::entityQuery('node');
      $query->condition('type', 'shoot_number');
      $query->condition('status', 1);
      $query->condition('field_yeekee_round', $round_id);
      // $query->condition('changed', $end_time, '<=');

      $start_time = new \DateTime();
      $start_time->setTime(6, 0);
      
      $query->condition('changed', [$start_time->getTimestamp(), $end_time], 'BETWEEN');
      $nids = $query->execute();

      if(!empty($nids)){
          $nodes = Node::loadMultiple($nids);

          $numbers = array();
          foreach ($nodes as $node) {
              $nid = $node->id();
              $number = $node->label();
              $uid = $node->getOwnerId();
              $user = User::load($uid);
              $user_name = $user->getUsername();
              // dpm($user_name);
  
              $changed = $node->getChangedTime();
              // dpm($changed);

              $numbers[] = array('nid'=>$node->id(), 'uid'=>$uid, 'number'=>$number, 'name'=>$user_name, 'update'=>$changed);
          }  

          $data = array();
          $data['round_id']      = $round_id;
          $data['numbers']       = $numbers;
          
          $filter = array('round_id'=>$round_id);
          if($collection->count($filter)){
              $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
              $collection->updateOne($filter, array('$set' =>$data) );
          }else{
              // create
              $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
              $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
              $collection->insertOne($data);
          }
      }
    }
  }

  // สถานะการฝาก & ถอนเงิน
  public function mongodb_deposit_status(){
    $type = 'taxonomy_term';
    $cid  = 'deposit_status';
    $branchs_terms = \Drupal::entityManager()->getStorage($type)->loadTree($cid);

    $collection = Utils::GetMongoDB()->deposit_status;
    foreach($branchs_terms as $tag_term) {
      $data = array();
      $data['tid']    = $tag_term->tid;
      $data['name']   = $tag_term->name;

      $filter = array('tid'=>$tag_term->tid);
      if($collection->count($filter)){
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->updateOne($filter, array('$set' =>$data) );
      }else{
          // create
          $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
          $collection->insertOne($data);
      }
    }
  }

  ///////////////////////////  Clear  ///////////////////////////////////
  // เครียส์ รายการโพยหวย ( ส่วนของลูกค้า ) , รายการโพยหวย ( ส่วนของเจ้ามือ ) ทั้งหมด
  public static function clear_bet_chits_dealers(){
    $query = \Drupal::entityQuery('user');
    $uids = $query->condition('uid', array(0), 'NOT IN')->execute();

    foreach ($uids as $i=>$uid){
        /// dpm($uid);

        $user = User::load( $uid );
        foreach ($user->get('field_chits')->getValue() as $bi=>$bv){
            $p = Paragraph::load( $bv['target_id'] );

            if(!empty($p)){
                $chit_id = $p->field_chit_id->value;

                $node = \Drupal::entityTypeManager()->getStorage('node')->load($chit_id);
                // Check if node exists with the given nid.
                if ($node) {
                    $node->delete();
                }

                $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
                if ($entity) $entity->delete();
            }
        }

        foreach ($user->get('field_list_lotterys')->getValue() as $bi=>$bv){
            $p = Paragraph::load( $bv['target_id'] );
            if(!empty($p)){
                $chit_id = $p->field_chit_id->value;

                $node = \Drupal::entityTypeManager()->getStorage('node')->load($chit_id);
                // Check if node exists with the given nid.
                if ($node) {
                    $node->delete();
                }

                $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
                if ($entity) $entity->delete();
            }
        }

        $user->field_chits = array();
        $user->field_list_lotterys = array();
        $user->save();
    }
  }

  // Clear user acess all
  public static function clear_user_access(){
    $query = \Drupal::entityQuery('user');
    $uids = $query->condition('uid', array(0), 'NOT IN')->execute();
    foreach ($uids as $i=>$uid){
      $user = User::load( $uid );
      foreach ($user->get('field_user_access')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );
        if(!empty($p)){
            $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
            if ($entity) $entity->delete();
        }
      }

      $user->field_user_access = array();
      $user->save();
    }
  }

  // Clear รายการถอนเงิน ทั้งหมด field_withdraw
  public static function clear_user_withdraw(){
    $query = \Drupal::entityQuery('user');
    $uids = $query->condition('uid', array(0), 'NOT IN')->execute();
    foreach ($uids as $i=>$uid){
      $user = User::load( $uid );
      foreach ($user->get('field_withdraw')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );
        if(!empty($p)){
            $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
            if ($entity) $entity->delete();
        }
      }

      $user->field_withdraw = array();
      $user->save();
    }
  }

  // Clear รายการฝากเงิน ทั้งหมด field_deposit
  public static function clear_user_deposit(){
    $query = \Drupal::entityQuery('user');
    $uids = $query->condition('uid', array(0), 'NOT IN')->execute();
    foreach ($uids as $i=>$uid){
      $user = User::load( $uid );
      foreach ($user->get('field_deposit')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );
        if(!empty($p)){
            $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
            if ($entity) $entity->delete();
        }
      }

      $user->field_deposit = array();
      $user->save();
    }
  }

  // Clear รายชือธนาคาร ทั้งหมด field_bank
  public static function clear_user_bank(){
    $query = \Drupal::entityQuery('user');
    $uids = $query->condition('uid', array(0), 'NOT IN')->execute();
    foreach ($uids as $i=>$uid){
      $user = User::load( $uid );
      foreach ($user->get('field_bank')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );
        if(!empty($p)){
            $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
            if ($entity) $entity->delete();
        }
      }

      $user->field_bank = array();
      $user->save();
    }
  }
  ///////////////////////////  Clear  ///////////////////////////////////

  public static function check_user_access(){
    $cursor = Utils::GetMongoDB()->user_socket_id->find();

    $uids = array();
    foreach ($cursor as $document) {
      $uids[] =  $document['uid'];
    }

    return 'User online :' . count($cursor) . ', uid : ' . implode(", ",$uids);;
    // foreach ($cursor as $document) {
    //   dpm( $document['uid'] );
    // }
  }

  /*
    ดึง logs จาก mongodb
  */ 
  public static function fetch_mg_log(){
    $cursor = Utils::GetMongoDB()->logs->find();

    $ids = array();
    foreach ($cursor as $document) {
      $ids[] =  $document['_id'];// . " >> " . $document['text'];
    }

    return implode(", ", $ids);
  }

  /*
  การดึง tid ของรอบทีออกล่าสุด
  */
  public static function get__taxonomy_term_tid__by_time(){
    // โหลด  รอบที่ 1
    $term = Term::load(31);

    $date1 = new \DateTime();
    $date1->setTimestamp($term->get('field_time_answer')->value);

    $date2 = new \DateTime();
    // $date2->setTimestamp('1595277900');

    $interval = $date1->diff($date2);
    // dpm( $interval );

    $minutes = $interval->days * 24 * 60;
    $minutes += $interval->h * 60;
    $minutes += $interval->i;
    // echo $minutes.' minutes';

    $branchs_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('yeekee_round');
    $tid = 0;
    foreach($branchs_terms as $tag_term) {
      if(strcmp($tag_term->name, (floor($minutes/15) + 1) ) == 0){
        $tid = $tag_term->tid;
        break;
      }
    }
    return $tid;
  }

  /*
  การดึง tid ของรอบปัจจุบัน
  */
  public static function get__taxonomy_term_tid_current__by_time(){
    // โหลด  รอบที่ 1
    $term = Term::load(31);

    $date1 = new \DateTime();
    $date1->setTimestamp($term->get('field_time_answer')->value);

    $date2 = new \DateTime();
    // $date2->setTimestamp('1595277900');

    $interval = $date1->diff($date2);
    // dpm( $interval );

    $minutes = $interval->days * 24 * 60;
    $minutes += $interval->h * 60;
    $minutes += $interval->i;
    // echo $minutes.' minutes';

    $branchs_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('yeekee_round');
    $tid = 0;
    foreach($branchs_terms as $tag_term) {
      if(strcmp($tag_term->name, (floor($minutes/15) + 2) ) == 0){
        $tid = $tag_term->tid;
        break;
      }
    }
    return $tid;
  }

  public static function awardYeekee($round_tid){
    if(empty($round_tid)){
      return;
    }

    $collection = Utils::GetMongoDB()->shoot_numbers;
    $cursor     = $collection->find(['round_id'=>$round_tid]);

    $result = $cursor->toArray();
    foreach($result as $i => $doc) {
      $result[$i] = MongoDB\BSON\toJSON(MongoDB\BSON\fromPHP($doc));
    }

    $numbers = json_decode($result[0])->numbers;
    usort($numbers, function($firstItem, $secondItem) {
            $timeStamp1 =$firstItem->created;
            $timeStamp2 =$secondItem->created;
            return $timeStamp2 - $timeStamp1;
        });

    $sum = 0;
    // $p1  = 0;
    $p16 = 0;
    foreach( $numbers as $key => $value) {
        $sum += $value->number;
        if($key == 15){
          $p16 = $value->number;
        }
    }

    dpm( 'Sum :' . $sum . ', P16 :' . $p16 .', P1 :' . $p1);
  }

  // Get shoot_numbers จาก mongodb เพือบันทึกลงที่ d8
  public static function getShootNumberByRound($round_tid){
    if(empty($round_tid)){
      return;
    }

    $collection = Utils::GetMongoDB()->shoot_numbers;
    $cursor     = $collection->find(['round_id'=>$round_tid]);

    $result = $cursor->toArray();
    foreach($result as $i => $doc) {
      $result[$i] = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($doc));
    }

    // $numbers = json_decode($result[0])->numbers;
    // usort($numbers, function($firstItem, $secondItem) {
    //         $timeStamp1 =$firstItem->created;
    //         $timeStamp2 =$secondItem->created;
    //         return $timeStamp2 - $timeStamp1;
    //     });

    usort($result,  function($firstItem, $secondItem) {
                      $timeStamp1 =json_decode($firstItem)->created;
                      $timeStamp2 =json_decode($secondItem)->created;

                      return $timeStamp2 - $timeStamp1;
                    }
          );

    ////////////////  insert to mongodb
    
    $date = 0;
    foreach(\Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('yeekee_round') as $ytag_term) {
      if(strcmp($ytag_term->tid, $round_tid) == 0){
        $term = Term::load($ytag_term->tid);
        $date = $term->field_time_answer->value * 1000;
        break;
      }
    }

    $p1  = 0;
    $p16 = 0;
    $sum = 0;
    foreach($result as $i => $value) {
      $value = json_decode( $value );
      $sum +=$value->number;
      switch($i){
        case 0:{
          $p1 = $value;
          break;
        }
      
        case 15:{
          $p16 = $value;
          break;
        }
      }
    }

    $data = array();
    $data['type_lotterys'] = '67';
    $data['round_tid']     = $round_tid;
    $data['date']          = strval($date);
    $data['data']          = (object)array('p1'=>json_encode($p1), 'p16'=>json_encode($p16), 'sum'=>$sum);

    /*
      $response['data']['p1']       = base64_encode(json_encode($p1));  
      $response['data']['p16']      = base64_encode(json_encode($p16));  
      $response['data']['sum']      = base64_encode($sum);  
    */
    
    $collection = Utils::GetMongoDB()->awards;
    $filter = array('round_tid'=>$round_tid, 'date'=>$date);

    // \Drupal::logger('huay')->notice(serialize($filter));
    if($collection->count($filter)){
        // udpate
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $collection->updateOne($filter, array('$set' =>$data) );
    }else{
        // create
        $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000);
        $collection->insertOne($data);
    }
    ////////////////   insert to mongodb

    // save string to file 
    // https://drupal.stackexchange.com/questions/231554/how-can-i-get-the-file-id-if-i-use-the-file-save-data-function
    
    // $file = file_save_data('content', 'public://file_uploads/example.txt', FILE_EXISTS_RENAME);

    // dpm( $file );

      
    // $file = file_directory_temp(). "/".$round_tid."_". date('m-d-Y_hia') .".txt";
    // $data = json_encode(base64_encode($result));
    // file_put_contents($file, $data);

    // dpm( $contents );
    $shoot_numbers = "public://shoot_numbers";

    if(!file_exists($shoot_numbers)){
      mkdir($shoot_numbers, 0777);
    }

    $filesaved = file_save_data( json_encode($result), $shoot_numbers . "/". $round_tid ."_". date('m-d-Y_hia') .".txt", 'FILE_EXISTS_REPLACE');  
    return $filesaved;
  }

  public static function autoShootNumber(){

    $collection = Utils::GetMongoDB()->shoot_numbers;
    $sn = array();
    $yeekee_round_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('yeekee_round');
    $data = array();
    foreach($yeekee_round_terms as $yeekee_round_tag_term) {
      $data[] = $yeekee_round_tag_term->tid;
    }

    sort($data);
    for ($x = 0; $x <= 10000; $x++) {
      $round_tid = rand( $data[0], $data[ count($data) -1 ] );
      if(in_array($round_tid, $data)){
        $term = Term::load($round_tid);
        $sn[] =   [
            'round_id' => strval($round_tid),
            'number'   => Utils::generateRandomString(TRUE,5),
            'uid'      => '7',
            // 'created'=>(new \DateTime('now'))->getTimestamp() * 1000,
            'date'     => $term->field_time_answer->value ,
            'createdAt'=>new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000),
            'updatedAt'=>new \MongoDB\BSON\UTCDateTime((new \DateTime('now'))->getTimestamp()*1000),

          ];
      }
    }

    $collection->insertMany($sn);
  }

  function generateRandomString($is_number = FALSE, $length = 10) {
    if($is_number){
      $characters = '0123456789';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
