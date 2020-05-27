<?php
namespace Drupal\huay\Utils;

use \Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use \Drupal\config_pages\Entity\ConfigPages;
use Drupal\user\Entity\User;
use Drupal\paragraphs\Entity\Paragraph;

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

  public static function mongodb_people($uid){
    if(empty($uid)){
      return FALSE;
    }

    // เราต้องแปลงเป้น string เพราะว่า เวลาเรา mongo filter จะมอง int กับ string ไม่เท่ากัน
    $uid = is_int($uid) ? (string)$uid : $uid;

    $data = array();
    $user = User::load($uid);

    $image_url = '';  
    $user_picture = $user->get('user_picture')->getValue();
    if (!empty($user_picture)) {
        $image_url = Utils::get_file_url($user_picture[0]['target_id']);
    }

    // $credit_balance = 0;
    // $field_credit_balance = $user->get('field_credit_balance')->getValue();
    // if(!empty($field_credit_balance)){
    //     $credit_balance = $field_credit_balance[0]['value'];
    // }

    $data = array(
                'uid'      =>$uid,
                'name'     =>$user->getUsername(),
                'email'    =>$user->getEmail(),
                'roles'    =>$user->getRoles(),
                'image_url'=>$image_url,
                'credit_balance' =>credit_balance($user),
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

    $user_access = array();
    foreach ($user->get('field_user_access')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );

        // cookie
        $cookie = '';
        $field_cookie = $p->get('field_cookie')->getValue();
        if(!empty($field_cookie)){
            $cookie = $field_cookie[0]['value'];
        }

        // field_socket_id
        $socket_id = '';
        $field_socket_id = $p->get('field_socket_id')->getValue();
        if(!empty($field_socket_id)){
            $socket_id = $field_socket_id[0]['value'];
        }
        
        $user_access[$bv['target_id']] = array('cookie' =>$cookie, 'socket_id' =>$socket_id);
    }

    $data['user_access'] = $user_access;

    // ฝากเงิน field_deposit
    $deposit = array();
    foreach ($user->get('field_deposit')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );

        // ID ธนาคารของเว็บฯ 
        $hauy_id_bank = '';
        $field_hauy_id_bank = $p->get('field_hauy_id_bank')->getValue();
        if(!empty($field_hauy_id_bank)){
            $hauy_id_bank = $field_hauy_id_bank[0]['value'];
        }

        // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า 
        $user_id_bank = '';
        $field_user_id_bank = $p->get('field_user_id_bank')->getValue();
        if(!empty($field_user_id_bank)){
            $user_id_bank = $field_user_id_bank[0]['value'];
        }

        // จำนวนเงินที่โอน
        $amount_of_money = '';
        $field_amount_of_money = $p->get('field_amount_of_money')->getValue();
        if(!empty($field_amount_of_money)){
            $amount_of_money = $field_amount_of_money[0]['value'];
        }

        // ช่องทางการโอนเงิน 
        $transfer_method = $p->get('field_transfer_method')->target_id;
       
        // วัน & เวลา ที่โอน 
        $date_transfer = '';
        $field_date_transfer = $p->get('field_date_transfer')->getValue();
        if(!empty($field_date_transfer)){
            $date_transfer = $field_date_transfer[0]['value'];
        }

        // หมายเหตุ 
        $annotation = '';
        $field_annotation = $p->get('field_annotation')->getValue();
        if(!empty($field_annotation)){
            $annotation = $field_annotation[0]['value'];
        }

        // สถานะการฝากเงิน 
        $deposit_status = $p->get('field_deposit_status')->target_id;

        $deposit[$bv['target_id']] = array( 'hauy_id_bank'      =>$hauy_id_bank, 
                                            'user_id_bank'      =>$user_id_bank,
                                            'amount_of_money'   =>$amount_of_money,
                                            'transfer_method'   =>$transfer_method,
                                            'date_transfer'     =>$date_transfer,
                                            'annotation'        =>$annotation,
                                            'deposit_status'    =>$deposit_status,
                                            );
    }
    $data['deposit'] = $deposit;

    // ถอนเงิน 
    $withdraw = array();
    foreach ($user->get('field_withdraw')->getValue() as $bi=>$bv){
        $p = Paragraph::load( $bv['target_id'] );

        // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า 
        $user_id_bank = '';
        $field_user_id_bank = $p->get('field_user_id_bank')->getValue();
        if(!empty($field_user_id_bank)){
            $user_id_bank = $field_user_id_bank[0]['value'];
        }

        // จำนวนเงินที่ถอน
        $amount_of_withdraw = '';
        $field_amount_of_withdraw = $p->get('field_amount_of_withdraw')->getValue();
        if(!empty($field_amount_of_withdraw)){
            $amount_of_withdraw = $field_amount_of_withdraw[0]['value'];
        }

        // หมายเหตุ 
        $annotation = '';
        $field_annotation = $p->get('field_annotation')->getValue();
        if(!empty($field_annotation)){
            $annotation = $field_annotation[0]['value'];
        }

        // สถานะการฝากเงิน 
        $withdraw_status = $p->get('field_withdraw_status')->target_id;

        $withdraw[$bv['target_id']] = array('user_id_bank'      =>$user_id_bank,
                                            'amount_of_withdraw'=>$amount_of_withdraw,
                                            'annotation'        =>$annotation,
                                            'withdraw_status'   =>$withdraw_status,
                                            );
    }
    $data['withdraw'] = $withdraw;

    /*
    if(in_array("lottery_dealer", $user->getRoles())){
        $list_lotterys = array();
        foreach ($user->get('field_list_lotterys')->getValue() as $bi=>$bv){
            $p = Paragraph::load( $bv['target_id'] );

            $chit_id = 0;
            $field_chit_id = $p->get('field_chit_id')->getValue();
            if(!empty($field_chit_id)){
                $chit_id = $field_chit_id[0]['value'];

                $p_chit = Paragraph::load( $chit_id );

                
                
                $chit_type_tid      = $p_chit->get('field_chit_type')->target_id;    // ประเภทโพยหวย
                $yeekee_round_tid   = $p_chit->get('field_yeekee_round')->target_id; // รอบ
                $chit_status_tid    = $p_chit->get('field_chit_status')->target_id;  // สถานะโพย

                $list_bet = array();
                foreach ($p_chit->get('field_list_bet')->getValue() as $pi=>$pv){
                    $p_bet = Paragraph::load($pv['target_id']);
                    $bet_type_tid      = $p_bet->get('field_bet_type')->target_id; // สามตัวบน, สามตัวล่าง 
                    
                    $bet_item = array();
                    foreach ($p_bet->get('field_bet_item')->getValue() as $bi=>$bv){
                        $p_bet_item = Paragraph::load($bv['target_id']);

                        $item_chit_price = 0;
                        $field_item_chit_price = $p_bet_item->get('field_item_chit_price')->getValue();
                        if(!empty($field_item_chit_price)){
                            $item_chit_price = $field_item_chit_price[0]['value'];
                        }

                        $item_chit_number = 0;
                        $field_item_chit_number = $p_bet_item->get('field_item_chit_number')->getValue();
                        if(!empty($field_item_chit_number)){
                            $item_chit_number = $field_item_chit_number[0]['value'];
                        }

                        $bet_item[$bv['target_id']] = array(
                                                            'item_chit_price' =>$item_chit_price,
                                                            'item_chit_number'=>$item_chit_number
                                                            );
                    }

                    $list_bet = array(
                                    'bet_type_tid'=>$bet_type_tid,
                                    'bet_item'    =>$bet_item
                                    );
                }

                $list_lotterys[$chit_id] = array(
                                                'chit_type_tid'    =>$chit_type_tid,
                                                'yeekee_round_tid' =>$yeekee_round_tid,
                                                'chit_status_tid'  =>$chit_status_tid,
                                                'list_bet'         =>$list_bet
                                                );
            }
        }

        $data['list_lotterys'] = $list_lotterys;
    }
    */

    // รายการโพยหวย ( ส่วนของลูกค้า )
    $chits = array();
    foreach ($user->get('field_chits')->getValue() as $bi=>$bv){
      $p = Paragraph::load( $bv['target_id'] );

      $chit_id = 0;
      $field_chit_id = $p->get('field_chit_id')->getValue();
      if(!empty($field_chit_id)){
        $chit_id = $field_chit_id[0]['value'];

        $node = Node::load($chit_id);
        if(!empty($node)){
            $name = $node->label();

            // สถานะโพย รอดำเนินการ/ยกเลิก/อนุมัติ
            $chit_status_name = '';
            $status_id  = $node->get('field_chit_status')->target_id;
            if(empty($status_id)){
              continue;
            }
            $chit_status_term = Term::load($status_id);
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
                              
                              'chit_status_name' => $chit_status_name,
                              'chit_type_name'   => $chit_type_name,
                              'round_name'=> $round_name,
                              'round_id'=>$round_id,
                              'note'      => $note,
                              'changed'   => $node->getChangedTime(),
                              'list_bet'  => $list_bet);
        } 
      }
    }
    $data['chits'] = $chits;

    // รายการโพยหวย ( ส่วนของเจ้ามือ )
    $dealers = array();
    foreach ($user->get('field_list_lotterys')->getValue() as $bi=>$bv){
      $p = Paragraph::load( $bv['target_id'] );

      $chit_id = 0;
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
    $data['dealers'] = $dealers;

    $collection = Utils::GetMongoDB()->people;
    $filter = array('uid'=>$uid);

    \Drupal::logger('huay')->notice(serialize($filter));
    if($collection->count($filter)){
        // udpate
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime($today))->getTimestamp()*1000);
        $collection->updateOne($filter, array('$set' =>$data) );
    }else{
        // create
        $data['createdAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime($today))->getTimestamp()*1000);
        $data['updatedAt']=new \MongoDB\BSON\UTCDateTime((new \DateTime($today))->getTimestamp()*1000);
        $collection->insertOne($data);
    }
    return TRUE;
  }
}
