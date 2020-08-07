<?php

/**
 * @file
 * Contains \Drupal\test_api\Controller\APIController.
 */

namespace Drupal\huay\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\paragraphs\Entity\Paragraph;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\file\Entity\File;

use Drupal\config_pages\Entity\ConfigPages;

use Drupal\huay\Utils\Utils;
/**
 * Controller routines for test_api routes.
 * https://www.chapterthree.com/blog/custom-restful-api-drupal-8
 */
class API extends ControllerBase {

  /*
  https://github.com/BoldizArt/D8_Register-Login/blob/master/src/Controller/RegisterLoginController.php
  
  ////////////////
  use Drupal\huay\Utils\Utils;

  $collection = Utils::GetMongoDB()->lotterys;

  $cursor = $collection->find();

  $datas = array();
  foreach ( $cursor as $id => $value ){
    $data = array();
    $data['tid'] = $value['tid'];
    $data['name'] = $value['name'];
    $data['end_time'] = $value['end_time'];
    $data['is_open'] = $value['is_open'];
    $data['image_url'] = $value['is_open'];
    $data['type_lottery'] = $value['type_lottery'];
    if(isset($value['rounds'])){
      $data['rounds'] = $value['rounds'];
    }

    $datas[] = $data;
  }
  dpm($datas);
  ////////////////
  
  */

  public function login(Request $request){
    $time1 = microtime(true);

    if ( Utils::verify($request, FALSE) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $name = trim( $content['name'] );
      $pass = trim( $content['pass'] );
  
      if(!(( empty($name) && empty($pass) ) ||
           empty($name) ||
           empty($pass)
        )){
          
        /*
        * case is email with use user_load_by_mail reture name
        */
        if(\Drupal::service('email.validator')->isValid( $name )){
          $user_load = user_load_by_mail($name);
          if(!$user_load){
            $response['result']   = FALSE;
            $response['message']  = 'Unrecognized ' . $name;
            return new JsonResponse( $response );
          }
          $name = user_load_by_mail($name)->getUsername();
        }
  
        $uid = \Drupal::service('user.auth')->authenticate($name, $pass);
        if(!empty($uid)){
          $user = User::load($uid);
          $user_login_finalize = user_login_finalize($user);
          
          // $image_url = '';  
          // if (!$user->get('user_picture')->isEmpty()) {
          //   $image_url = file_create_url($user->get('user_picture')->entity->getFileUri());
          // }

          // $cookie = $request->headers->get('session');

          $data = array('uid'      =>$uid, 
                        // 'name'     =>$user->getUsername(),
                        // 'email'    =>$user->getEmail(),
                        // 'roles'    =>$user->getRoles(),
                        // 'image_url'=>$image_url,
                        // 'session'  =>$cookie, //Utils::encode('uid='.$uid."&time=".\Drupal::time()->getCurrentTime()),
                        );

          /*
          กรณีที่ใช้ browser เดียวกัน login ได้หลายๆครั้งจะได้ cookie เดิมเสมอนอกจะ clear cache browser cookie ถึงจะเปลียนแปลง
          
          $is_new_cookie = TRUE;
          $paragraphs = array();
          foreach ($user->get('field_user_access')->getValue() as $ii=>$vv){
            $p = Paragraph::load( $vv['target_id'] );
            if(empty($p)){
              continue;
            }
            
            $paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());

            $field_cookie = $p->get('field_cookie')->getValue();
            if(!empty($field_cookie)){
              $tmp_cookie = $field_cookie[0]['value'];

              if(strcmp($tmp_cookie, $cookie) == 0){
                $is_new_cookie = FALSE;
                break;
              }
            }
          }

          if($is_new_cookie){
            // เก็บ user_access
            $user_access = Paragraph::create([
              'type'            => 'user_access',
              'field_cookie'    => $cookie,
            ]);
            $user_access->save();

            $paragraphs[] = array('target_id'=> $user_access->id(), 'target_revision_id' => $user_access->getRevisionId());

            $user->set('field_user_access', $paragraphs);
            $user->save();
            // เก็บ user_access
          }
          */

          // Utils::mongodb_people( $uid );
  
          $response['result']           = TRUE;
          $response['execution_time']   = microtime(true) - $time1;
          $response['data']             = $data;
          // $response['lotterys']      = Utils::mongodb_fetch_lotterys();
          
          return new JsonResponse( $response );
        }
      } 
    }

    $response['result']   = FALSE;
    $response['message']  = 'login';
    return new JsonResponse( $response );
  }

  /*
  https://api.drupal.org/api/drupal/core!modules!user!user.module/function/user_logout/8.2.x
  */
  public function logout(Request $request){
    $time1 = microtime(true);

    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid = trim( $content['uid']);

      if(!empty($uid)){
        // $user = \Drupal::currentUser();
        // $user = User::load($uid);

        /*
        \Drupal::logger('user')
          ->notice('Session closed for %name.', array(
          '%name' => $user
            ->getAccountName(),
        ));
        \Drupal::moduleHandler()
          ->invokeAll('user_logout', array(
          $user,
        ));

        // Destroy the current session, and reset $user to the anonymous user.
        // Note: In Symfony the session is intended to be destroyed with
        // Session::invalidate(). Regrettably this method is currently broken and may
        // lead to the creation of spurious session records in the database.
        // @see https://github.com/symfony/symfony/issues/12375
        \Drupal::service('session_manager')->destroy();
        $user->setAccount(new AnonymousUserSession());
        */

        /*
        $cookie = $request->headers->get('session');

        $user = User::load($uid);
        foreach ($user->get('field_user_access')->getValue() as $ii=>$vv){
          $p = Paragraph::load( $vv['target_id'] );

          if(!empty($p)){
            $field_cookie = $p->get('field_cookie')->getValue();
            if(!empty($field_cookie)){
              $tmp_cookie = $field_cookie[0]['value'];
  
              if(strcmp($tmp_cookie, $cookie) == 0){
                $p = Paragraph::load( $vv['target_id'] );
                if ($p) $p->delete();
                break;
              }
            }
          }
          
        }
        */

        $response['result']           = TRUE;
        $response['execution_time']   = microtime(true) - $time1; 
        return new JsonResponse( $response );
      }
    }
    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  public function register(Request $request){
    $time1 = microtime(true);

    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $name = trim( $content['name']);
      $pass = trim( $content['pass'] );

      if(!(( empty($name) && empty($pass) ) ||
            empty($name) ||
            empty($pass)
        )){

        /*
          * case is email with use user_load_by_mail reture name
        */
        if(!\Drupal::service('email.validator')->isValid( $name )){
          $response['result']   = FALSE;
          $response['message']  = t('The email address @email invalid.', array('@email' => $name))->__toString();
          return new JsonResponse( $response );
        }

        $user = user_load_by_mail($name);
        if(!empty($user)){
          $response['result']   = FALSE;
          $response['message']  = t('The email address @email is already taken.', array('@email' => $name))->__toString();
          return new JsonResponse( $response );
        }

        // Create user
        $user = User::create();

        // Mandatory settings
        $user->setPassword($pass);
        $user->set("langcode", 'en');
        $user->enforceIsNew();
        $user->setEmail($name);
        $user->setUsername(explode("@", $name)[0]);
        $user->addRole('authenticated');
        
        // Optional settings
        $user->activate();

        // Save user
        $user->save();

        // User login
        user_login_finalize($user);

        _user_mail_notify('register_no_approval_required', $user, 'en');

        $response['result']   = TRUE;
        $response['execution_time']   = microtime(true) - $time1;

        $response['data']      = $user;
        return new JsonResponse( $response );
      }
    }

    $response['result']   = FALSE;
    $response['message']  = 'register';
    return new JsonResponse( $response );
  }

  /* https://stackoverflow.com/questions/4247405/how-do-i-send-an-email-notification-when-programatically-creating-a-drupal-user/10603541
  * @param $op
  *   The operation being performed on the account. Possible values:
  *   - 'register_admin_created': Welcome message for user created by the admin.
  *   - 'register_no_approval_required': Welcome message when user
  *     self-registers.
  *   - 'register_pending_approval': Welcome message, user pending admin
  *     approval.
  *   - 'password_reset': Password recovery request.
  *   - 'status_activated': Account activated.
  *   - 'status_blocked': Account blocked.
  *   - 'cancel_confirm': Account cancellation request.
  *   - 'status_canceled': Account canceled.
  */
  public function reset_password(Request $request){
    $time1 = microtime(true);

    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $name = trim( $content['name']);

      if( empty($name) ){
        $response['result'] = FALSE;
        return new JsonResponse( $response );
      }

      $user = NULL;
      if(\Drupal::service('email.validator')->isValid( $name )){
        $user = user_load_by_mail($name);
        if(empty( $user )){
          $response['result']   = FALSE;
          $response['message']  = t('@email is not recognized an email address.', array('@email' => $name))->__toString();
          return new JsonResponse( $response );
        }
      }else{
        $user = user_load_by_name($name);
        if(empty( $user )){
          $response['result']   = FALSE;
          $response['message']  = t('@email is not recognized as a username.', array('@email' => $name))->__toString();
          return new JsonResponse( $response );
        }
      }

      // $name = $this->requestStack->getCurrentRequest()->query->get('name');
      // // TODO: Add destination.
      // // $page_destination = $this->requestStack->getCurrentRequest()->query->get('destination');

      // $langcode =  $this->languageManager->getCurrentLanguage()->getId();
      // // Try to load by email.
      // $users =  $this->entityTypeManager->getStorage('user')->loadByProperties(array('mail' => $name));
      // if (empty($users)) {
      //   // No success, try to load by name.
      //   $users =  $this->entityTypeManager->getStorage('user')->loadByProperties(array('name' => $name));
      // }
      $account = reset($user);
      // Mail one time login URL and instructions using current language.
      $mail = _user_mail_notify('password_reset', $account, 'en');

      // if (!empty($mail)) {
      //   $this->logger->notice('Password reset instructions mailed to %name at %email.', ['%name' => $account->getAccountName(), '%email' => $account->getEmail()]);
      //   $this->messenger->addStatus($this->t('Further instructions have been sent to your email address.'));
      // }

      $response['result']   = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      
      // $response['message']  = t('@id | @name |  @email', array('@id'=>$user->id(), '@name' => $user->getUsername(), '@email' => $user->getEmail()))->__toString();
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    $response['message']  = 'reset_password';
    return new JsonResponse( $response );
  }

  public function list_bank(Request $request){
    $time1 = microtime(true);

    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;

      $response['data']             = Utils::get_taxonomy_term('list_bank');
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    $response['message']  = 'list_bank';
    return new JsonResponse( $response );
  }

  public function add_bank(Request $request){
    $time1 = microtime(true);

    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid          = trim( $content['uid'] );
      $tid_bank     = trim( $content['tid_bank'] );
      $name_bank    = trim( $content['name_bank'] );
      $number_bank  = trim( $content['number_bank'] );

      if( empty($uid) || empty($tid_bank) || empty($name_bank) || empty($number_bank) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $number_bank = str_replace("-", "", $number_bank);

      $user_banks = Paragraph::create([
        'type'            => 'user_banks',
        'field_bank'      => $tid_bank,
        'field_name_bank' => $name_bank,
        'field_number_bank' => $number_bank
      ]);
      
      $user_banks->save();

      $user = User::load($uid);

      $paragraphs = array();
      foreach ($user->get('field_bank')->getValue() as $ii=>$vv){
          $p = Paragraph::load( $vv['target_id'] );

          $field_number_bank = $p->get('field_number_bank')->getValue();
          if(!empty($field_number_bank)){
            $field_number_bank = $field_number_bank[0]['value'];
          }
          $field_bank        = $p->get('field_bank')->target_id;
          if( strcmp($field_number_bank, $number_bank) === 0 && strcmp($field_bank, $tid_bank) === 0){
            $response['result']   = FALSE;
            $response['message']  = 'ธนาคาร & เลขที่บัญชี ซํ้า';
            return new JsonResponse( $response );
          }

          $paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      }

      $paragraphs[] = array('target_id'=> $user_banks->id(), 'target_revision_id' => $user_banks->getRevisionId());
    
      $user->set('field_bank', $paragraphs);
      $user->save();

      // str_replace("-", "", '123-3-33333-33333')
      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  public function delete_bank(Request $request){
    $time1 = microtime(true);

    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid          = trim( $content['uid'] );
      $target_id    = trim( $content['target_id'] );

      if( empty($uid) || empty($pid) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($target_id);
      if ($entity) $entity->delete();

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  public function update_socket_io(Request $request){
    $time1 = microtime(true);
    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid                = trim( $content['uid'] );
      $socket_id          = trim( $content['socket_id'] );

      if( empty($uid) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $cookie = $request->headers->get('session');

      $target_id = 0;
      $user = User::load($uid);
      foreach ($user->get('field_user_access')->getValue() as $ii=>$vv){
        $p = Paragraph::load( $vv['target_id'] );

        if(!empty($p)){
          $field_cookie = $p->get('field_cookie')->getValue();
          if(!empty($field_cookie)){
            $tmp_cookie = $field_cookie[0]['value'];

            if(strcmp($tmp_cookie, $cookie) == 0){
              $target_id = $vv['target_id'];
              break;
            }
          }
        }
      }

      if($target_id){
        $p = Paragraph::load( $target_id );
        if(!empty($p)){
          $p->set('field_socket_id', $socket_id);
          $p->save();
        }
      }

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  // ฝากเงิน 
  public function add_deposit(Request $request){
    $time1 = microtime(true);
    
    // \Drupal::logger('add_deposit >')->error(empty($_FILES) ? 'YES' : "NO");
    // \Drupal::logger('add_deposit')->error($_FILES['attached_file']['name']);
    // \Drupal::logger('add_deposit >')->error(serialize($_REQUEST));

    // 
    // \Drupal::logger('client_secret')->error($request->headers->get('client_secret'));

    // $target = 'sites/default/files/'. $_FILES['attached_file']['name'];
    // move_uploaded_file( $_FILES['attached_file']['tmp_name'], $target);

    // $file_temp = file_get_contents( $target );
    // $file = file_save_data($file_temp, 'public://'. date('m-d-Y_hia') .'.png' , FILE_EXISTS_RENAME);

    // $p->set('field_image', array('target_id'=>$file->id()));

    // \Drupal::logger('add_deposit >>')->error(serialize(json_decode( $request->getContent(), TRUE )));
    if ( Utils::verify($request, FALSE) ) {
      // $content = json_decode( $request->getContent(), TRUE );

      // \Drupal::logger('add_deposit')->error( $_POST['uid'] );
      /*
      $uid                = trim( $content['uid'] );
      $hauy_id_bank       = trim( $content['hauy_id_bank'] ); // ID ธนาคารของเว็บฯ
      $user_id_bank       = trim( $content['user_id_bank'] ); // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า
      $amount_of_money    = trim( $content['amount_of_money'] ); // จำนวนเงินที่โอน
      $transfer_method    = trim( $content['transfer_method'] ); // ช่องทางการโอนเงิน
      $date_transfer      = trim( $content['date_transfer'] ); // วัน & เวลา ที่โอน
      $annotation         = trim( $content['annotation'] ); // ID ธนาคารของเว็บฯ

      if( empty($uid) || 
          empty($hauy_id_bank) || 
          empty($user_id_bank) || 
          empty($amount_of_money) || 
          empty($transfer_method) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $user_deposit = Paragraph::create([
        'type'                    => 'user_deposit',
        'field_hauy_id_bank'      => $hauy_id_bank,
        'field_user_id_bank'      => $user_id_bank,
        'field_amount_of_money'   => $amount_of_money,
        'field_transfer_method'   => $transfer_method,
        // 'field_date_transfer'     => $date_transfer,
        'field_annotation'        => $annotation,
      ]);
      
      $user_deposit->save();

      $user = User::load($uid);

      $paragraphs = array();
      foreach ($user->get('field_deposit')->getValue() as $ii=>$vv){
          $p = Paragraph::load( $vv['target_id'] );
          $paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      }
      $paragraphs[] = array('target_id'=> $user_deposit->id(), 'target_revision_id' => $user_deposit->getRevisionId());
      
      $user->set('field_deposit', $paragraphs);
      $user->save();
      */

      $uid                = trim( $_POST['uid'] );
      $hauy_id_bank       = trim( $_POST['hauy_id_bank'] );     // ID ธนาคารของเว็บฯ
      $user_id_bank       = trim( $_POST['user_id_bank'] );     // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า
      $transfer_method    = trim( $_POST['transfer_method'] );  // ช่องทางการโอนเงิน
      $amount             = trim( $_POST['amount'] );           // จำนวนเงินที่โอน

      $date_transfer      = trim( $_POST['date_transfer'] );    // วัน-เวลาโอน

      
      // $attached_file      = '';  // ไฟล์แนบ field_attached_file
      $note               = trim( $_POST['note'] );             // หมายเหตุ

      if( empty($uid) || 
          empty($hauy_id_bank) || 
          empty($user_id_bank) || 
          empty($amount) || 
          empty($transfer_method) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $attached_file = array();
      if(!empty($_FILES)){
        $target = 'sites/default/files/'. $_FILES['attached_file']['name'];
        move_uploaded_file( $_FILES['attached_file']['tmp_name'], $target);

        $attached_file = file_save_data( file_get_contents( $target ), 'public://'. date('m-d-Y_hia') .'.png' , FILE_EXISTS_RENAME);
      }

      // $node->set('field_datetime', date('Y-m-d\TH:i:s', time()));

      $user = User::load($uid);
      $node = Node::create([
        'type'                   => 'user_deposit',
        'uid'                    => $uid,
        'status'                 => 1,
        'title'                  => "ฝากเงิน : " . $user->getUsername(),

        'field_huay_list_bank'   => $hauy_id_bank,        // ธนาคารของเว็บฯ ที่โอนเข้า
        'field_list_bank'        => $user_id_bank,        // ธนาคารที่ทำการโอนเงินเข้ามา
        'field_transfer_method'  => $transfer_method,     // ช่องทางการโอนเงิน
        'field_amount'           => $amount,              // จำนวนเงินที่โอน
        'field_attached_file'    => empty($attached_file) ? array() : array('target_id'=>$attached_file->id()),
        'field_date_transfer'    => date('Y-m-d\TH:i:s', $date_transfer/1000),       // วัน-เวลาโอน
        'body'                   => $note,                // หมายเหตุ
      ]);
      $node->save();
      
      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  public function withdraw(Request $request){
    $time1 = microtime(true);
    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid                = trim( $content['uid'] );
      $user_id_bank       = trim( $content['user_id_bank'] );       // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า
      $amount_of_withdraw = trim( $content['amount_of_withdraw'] ); // จำนวนเงินที่โอน
      $note         = trim( $content['note'] );         // ID ธนาคารของเว็บฯ

      if( empty($uid) ||  
          empty($user_id_bank) || 
          empty($amount_of_withdraw) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      /*
      $user_withdraw = Paragraph::create([
        'type'                    => 'user_withdraw',
        'field_user_id_bank'      => $user_id_bank,
        'field_amount_of_withdraw'=> $amount_of_withdraw,
        'field_annotation'        => $annotation,
      ]);
      
      $user_withdraw->save();

      $user = User::load($uid);
      $paragraphs = array();
      foreach ($user->get('field_withdraw')->getValue() as $ii=>$vv){
          $p = Paragraph::load( $vv['target_id'] );
          $paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      }
      $paragraphs[] = array('target_id'=> $user_withdraw->id(), 'target_revision_id' => $user_withdraw->getRevisionId());
      
      $user->set('field_withdraw', $paragraphs);
      $user->save();
      */

      $user = User::load($uid);
      $node = Node::create([
        'type'                   => 'user_withdraw',
        'uid'                    => $uid,
        'status'                 => 1,
        'title'                  => "ถอนเงิน : " . $user->getUsername(),

        'field_amount_of_withdraw'  => $amount_of_withdraw,   // จำนวนเงินที่ถอน
        'field_user_id_bank'        => $user_id_bank,         // บัญชีธนาคารของท่าน  
        'body'                      => $note,                 // หมายเหตุ
      ]);
      $node->save();

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  public function bet(Request $request){
    $time1 = microtime(true);
    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid                = trim( $content['uid'] );
      $data               = trim( $content['data'] ); 
      if( empty($uid) ||  
          empty($data) ||
          empty(User::load($uid)) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }
      
      $data_decode = json_decode(base64_decode($data));

      switch($data_decode->chit_type){
        // ยี่กี
        case 67:{
          // bet_yeekee()
          $this->bet_yeekee($uid, $data_decode);
          break;
        }

        default:{
          $this->bet_other($uid, $data_decode);
          break;
        }
      }

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  public function bet_yeekee($uid, $data_decode){
    $lottery_dealer = 1;

    $list_bet_paragraphs =array();
    foreach ($data_decode->data as $ii=>$vv){
      $item_chit_paragraphs = array();
      foreach ($vv->items as $item_key=>$item_value){
        // item_chit
        $item_chit = Paragraph::create([
          'type'                    => 'item_chit',
          'field_item_chit_price'   => $item_value->quantity,
          'field_item_chit_number'  => $item_value->number,      // รอบ
        ]);
        $item_chit->save();

        $item_chit_paragraphs[] = array('target_id'=> $item_chit->id(), 'target_revision_id' => $item_chit->getRevisionId());
      }

      $item_chit_type = 0;
      switch($vv->type){
        // สามตัวบน
        case 'type_3_up':{
          $item_chit_type = 21;
        break;
        }

        // สามตัวโต๊ด
        case 'type_3_toot':{
          $item_chit_type = 22;
        break;
        }

        // สองตัวบน
        case 'type_2_up':{
          $item_chit_type = 23;
        break;
        }

        // สองตัวล่าง
        case 'type_2_down':{
          $item_chit_type = 24;
        break;
        }

        // สามตัวกลับ
        case 'type_3_undo':{
          $item_chit_type = 25;
        break;
        }

        // สองตัวกลับ
        case 'type_2_undo':{
          $item_chit_type = 26;
        break;
        }

        // วิ่งบน
        case 'type_1_up':{
          $item_chit_type = 27;
        break;
        }

        // วิ่งล่าง
        case 'type_1_down':{
          $item_chit_type = 28;
        break;
        }
      }

      // list_bet
      $list_bet = Paragraph::create([
        'type'                   => 'list_bet',
        'field_bet_item'         => $item_chit_paragraphs,
        'field_bet_type'         => $item_chit_type
      ]);
      $list_bet->save();

      $list_bet_paragraphs[] = array('target_id'=> $list_bet->id(), 'target_revision_id' => $list_bet->getRevisionId());
    }

    /*
    $user = User::load($uid);
    $chits_paragraphs = array();
    foreach ($user->get('field_chits')->getValue() as $ii=>$vv){
        $p = Paragraph::load( $vv['target_id'] );
        $chits_paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
    }

    // chit
    $chit = Paragraph::create([
      'type'                   => 'chit',
      'field_yeekee_round'     => $data_decode->yeekee_round,       // รอบการแทงหวยยีกี
      'field_chit_type'        => $data_decode->chit_type,          // ยี่กี หรือ หวยรัฐบาลไทย
      'field_list_bet'         => $list_bet_paragraphs,
      'field_lottery_dealer'   => $lottery_dealer, // 
    ]);
    $chit->save();

    $chits_paragraphs[] = array('target_id'=> $chit->id(), 'target_revision_id' => $chit->getRevisionId());
    
    $user->set('field_chits', $chits_paragraphs);
    $user->save();
    */

    $user_lottery_customer = User::load($uid);              // ลูกค้า
    $user_lottery_dealer   = User::load($lottery_dealer);   // เจ้ามือหวย

    //           User::load($lottery_dealer)->getUsername();
    $node = Node::create([
      'type'                   => 'chits',
      'uid'                    => $uid,
      'status'                 => 1,
      'title'                  => "ลูกค้า : " . $user_lottery_customer->getUsername() ." > เจ้ามือ : ". $user_lottery_dealer->getUsername() ,
      'field_yeekee_round'     => $data_decode->round_tid,       // รอบการแทงหวยยีกี
      'field_chit_type'        => $data_decode->chit_type,          // ยี่กี หรือ หวยรัฐบาลไทย
      'field_list_bet'         => $list_bet_paragraphs,
      'field_lottery_dealer'   => $lottery_dealer, // 
      'field_lottery_custom'   => $uid
    ]);
    $node->save();

    // Update to custom
    $chits_paragraphs = array();
    foreach ($user_lottery_customer->get('field_chits')->getValue() as $ii=>$vv){
      $p = Paragraph::load( $vv['target_id'] );
      if(!empty($p)){
        $chits_paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      } 
    }

    $chit = Paragraph::create([
      'type'              => 'chit',
      'field_chit_id'     => $node->id(),       // รอบการแทงหวยยีกี
    ]);
    $chit->save();
    
    $chits_paragraphs[] = array('target_id'=> $chit->id(), 'target_revision_id' => $chit->getRevisionId());
    
    $user_lottery_customer->set('field_chits', $chits_paragraphs);
    $user_lottery_customer->save();
    // Update to custom

    // Update to dealer
    $list_lotterys_paragraphs = array();
    foreach ($user_lottery_dealer->get('field_list_lotterys')->getValue() as $ii=>$vv){
      $p = Paragraph::load( $vv['target_id'] );
      if(!empty($p)){
        $list_lotterys_paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      } 
    }

    $list_lottery = Paragraph::create([
      'type'              => 'list_lotterys',
      'field_chit_id'     => $node->id(),       // รอบการแทงหวยยีกี
    ]);
    $list_lottery->save();
    
    $list_lotterys_paragraphs[] = array('target_id'=> $list_lottery->id(), 'target_revision_id' => $list_lottery->getRevisionId());
    
    $user_lottery_dealer->set('field_list_lotterys', $list_lotterys_paragraphs);
    $user_lottery_dealer->save();
    // Update to dealer
  }

  public function bet_other($uid, $data_decode){

    $lottery_dealer = 1;

    $list_bet_paragraphs =array();
    foreach ($data_decode->data as $ii=>$vv){
      $item_chit_paragraphs = array();
      foreach ($vv->items as $item_key=>$item_value){
        // item_chit
        $item_chit = Paragraph::create([
          'type'                    => 'item_chit',
          'field_item_chit_price'   => $item_value->quantity,
          'field_item_chit_number'  => $item_value->number,      // รอบ
        ]);
        $item_chit->save();

        $item_chit_paragraphs[] = array('target_id'=> $item_chit->id(), 'target_revision_id' => $item_chit->getRevisionId());
      }

      $item_chit_type = 0;
      switch($vv->type){
        // สามตัวบน
        case 'type_3_up':{
          $item_chit_type = 21;
        break;
        }

        // สามตัวโต๊ด
        case 'type_3_toot':{
          $item_chit_type = 22;
        break;
        }

        // สองตัวบน
        case 'type_2_up':{
          $item_chit_type = 23;
        break;
        }

        // สองตัวล่าง
        case 'type_2_down':{
          $item_chit_type = 24;
        break;
        }

        // สามตัวกลับ
        case 'type_3_undo':{
          $item_chit_type = 25;
        break;
        }

        // สองตัวกลับ
        case 'type_2_undo':{
          $item_chit_type = 26;
        break;
        }

        // วิ่งบน
        case 'type_1_up':{
          $item_chit_type = 27;
        break;
        }

        // วิ่งล่าง
        case 'type_1_down':{
          $item_chit_type = 28;
        break;
        }
      }

      // list_bet
      $list_bet = Paragraph::create([
        'type'                   => 'list_bet',
        'field_bet_item'         => $item_chit_paragraphs,
        'field_bet_type'         => $item_chit_type
      ]);
      $list_bet->save();

      $list_bet_paragraphs[] = array('target_id'=> $list_bet->id(), 'target_revision_id' => $list_bet->getRevisionId());
    }

    /*
    $user = User::load($uid);
    $chits_paragraphs = array();
    foreach ($user->get('field_chits')->getValue() as $ii=>$vv){
        $p = Paragraph::load( $vv['target_id'] );
        $chits_paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
    }

    // chit
    $chit = Paragraph::create([
      'type'                   => 'chit',
      'field_yeekee_round'     => $data_decode->yeekee_round,       // รอบการแทงหวยยีกี
      'field_chit_type'        => $data_decode->chit_type,          // ยี่กี หรือ หวยรัฐบาลไทย
      'field_list_bet'         => $list_bet_paragraphs,
      'field_lottery_dealer'   => $lottery_dealer, // 
    ]);
    $chit->save();

    $chits_paragraphs[] = array('target_id'=> $chit->id(), 'target_revision_id' => $chit->getRevisionId());
    
    $user->set('field_chits', $chits_paragraphs);
    $user->save();
    */

    $user_lottery_customer = User::load($uid);              // ลูกค้า
    $user_lottery_dealer   = User::load($lottery_dealer);   // เจ้ามือหวย

    //           User::load($lottery_dealer)->getUsername();
    $node = Node::create([
      'type'                   => 'chits',
      'status'                 => 1,
      'title'                  => "ลูกค้า : " . $user_lottery_customer->getUsername() ." > เจ้ามือ : ". $user_lottery_dealer->getUsername() ,
      // 'field_yeekee_round'     => $data_decode->round_tid,       // รอบการแทงหวยยีกี
      'field_chit_type'        => $data_decode->chit_type,          // ยี่กี หรือ หวยรัฐบาลไทย 
      'field_list_bet'         => $list_bet_paragraphs,
      'field_lottery_dealer'   => $lottery_dealer, // 
      'field_lottery_custom'   => $uid
    ]);
    $node->save();

    // Update to custom
    $chits_paragraphs = array();
    foreach ($user_lottery_customer->get('field_chits')->getValue() as $ii=>$vv){
        $p = Paragraph::load( $vv['target_id'] );
        $chits_paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
    }

    $chit = Paragraph::create([
      'type'              => 'chit',
      'field_chit_id'     => $node->id(),       // รอบการแทงหวยยีกี
    ]);
    $chit->save();
    
    $chits_paragraphs[] = array('target_id'=> $chit->id(), 'target_revision_id' => $chit->getRevisionId());
    
    $user_lottery_customer->set('field_chits', $chits_paragraphs);
    $user_lottery_customer->save();
    // Update to custom

    // Update to dealer
    $list_lotterys_paragraphs = array();
    foreach ($user_lottery_dealer->get('field_list_lotterys')->getValue() as $ii=>$vv){
        $p = Paragraph::load( $vv['target_id'] );
        $list_lotterys_paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
    }

    $list_lottery = Paragraph::create([
      'type'              => 'list_lotterys',
      'field_chit_id'     => $node->id(),       // รอบการแทงหวยยีกี
    ]);
    $list_lottery->save();
    
    $list_lotterys_paragraphs[] = array('target_id'=> $list_lottery->id(), 'target_revision_id' => $list_lottery->getRevisionId());
    
    $user_lottery_dealer->set('field_list_lotterys', $list_lotterys_paragraphs);
    $user_lottery_dealer->save();
    // Update to dealer
  }

  public function bet_cancel(Request $request){
    $time1 = microtime(true);
    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );
      $uid                = trim( $content['uid'] );
      $nid               = trim( $content['nid'] ); 
      if( empty($uid) ||  
          empty($nid) ||
          empty(User::load($uid)) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }
      
      $node = Node::load($nid);

      $chit_status = $node->field_chit_status->target_id;
      if(strcmp($chit_status, 55) == 0){
        // update status ยกเลิก
        $node->field_chit_status = 56;
        $node->setPublished(FALSE);
        $node->save();

        // ลบ ออกจากลูกค้า 
        $lottery_custom = User::load($node->field_lottery_custom->target_id);
        $parag_chits  =array();
        foreach($lottery_custom->get('field_chits')->getValue() as $i=>$v){
          $p = Paragraph::load( $v['target_id'] );

          if(!empty($p)){
            $chit_id = $p->field_chit_id->value;
          
            if(strcmp($nid, $chit_id) == 0){
              $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
              if ($entity) $entity->delete();
            }else{
              
              $parag_chits[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
            }
          }
        }

        $lottery_custom->field_chits = $parag_chits;
        $lottery_custom->save();

        // Update mongodb user 
        // Utils::mongodb_people($node->field_lottery_custom->target_id);

        // ลบ ออกจากเจ้ามือ
        $lottery_dealer = User::load($node->field_lottery_dealer->target_id);
        $parag_list_lotterys  =array();
        foreach($lottery_dealer->get('field_list_lotterys')->getValue() as $i=>$v){
          $p = Paragraph::load( $v['target_id'] );

          if(!empty($p)){
            $chit_id = $p->field_chit_id->value;
          
            if(strcmp($nid, $chit_id) == 0){
              $entity = \Drupal::entityTypeManager()->getStorage('paragraph')->load($v['target_id']);
              if ($entity) $entity->delete();
            }else{
              $parag_list_lotterys[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
            }
          }
        }

        $lottery_dealer->field_list_lotterys = $parag_list_lotterys;
        $lottery_dealer->save();

        // Update mongodb user 
        // Utils::mongodb_people($node->field_lottery_dealer->target_id);
      }

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  // ยิงเลขหวยยี่กี่
  /*
  public function shoot_number(Request $request){

    $time1 = microtime(true);
    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid                = trim( $content['uid'] );
      $data               = trim( $content['data'] ); 
      $round_tid          = trim( $content['round_tid'] );
  
      $user = User::load($uid);
      if( empty($uid) ||  
          empty($data) ||
          empty($user) ||
          empty($round_tid) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $node = Node::create([
        'type'                   => 'shoot_number',
        'uid'                    => $uid,
        'status'                 => 1,
        'title'                  => $data, 
        'field_yeekee_round'     => $round_tid,
      ]);
      $node->save();

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }
  */

  public function request_all(Request $request){

    $time1 = microtime(true);
    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );
      $uid                = trim( $content['uid'] );
      
      $user = User::load($uid);
      if( empty($uid) ||  
          empty($user) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      $response['datas']            = Utils::get_user_deposit($uid);
      return new JsonResponse( $response );
    }

    $response['result']     = FALSE;
    return new JsonResponse( $response );
  }

  // https://crontab.guru/every-15-minutes
  public function every_15_minute(Request $request){
    $mode = \Drupal::request()->query->get('mode');
    // if(empty($mode) || strcmp(Utils::decode($mode), 'cron') !== 0 ){
    //   \Drupal::logger('every_15_minute')->error('not cron');
    //   $response['result']  = FALSE;
    //   return new JsonResponse( $response );
    // }

    Utils::mongodb_lotterys('&');
    
    \Drupal::logger('every_15_minute')->notice('is cron ' . Utils::get__taxonomy_term_tid__by_time() );

    // บันทึกผลการออกหวย ยี่กี่ yeekee_answer
    
    //  base64_encode(json_encode($numbers)) 
    // --------- d8
    $yeekee_answer = ConfigPages::config('yeekee_answer');
    $answer_yks = array();
    foreach ($yeekee_answer->get('field_answer_yk')->getValue() as $ii=>$vv){
      $p = Paragraph::load( $vv['target_id'] );
      if(!empty($p)){
        $answer_yks[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      }
    }

    $round_tid = Utils::get__taxonomy_term_tid__by_time();

    // 
    $fid_shoot_number_txt = Utils::getShootNumberByRound($round_tid)->id();

    $item_yeekee_answer = Paragraph::create([
                                              'type'               => 'item_yeekee_answer',
                                              'field_shoot_number_txt' => [
                                                'target_id' => $fid_shoot_number_txt,
                                              ],
                                              'field_round_ye'     => $round_tid,
                                            ]);
    $item_yeekee_answer->save();

    $answer_yks[] = array('target_id'=> $item_yeekee_answer->id(), 'target_revision_id' => $item_yeekee_answer->getRevisionId());

    $yeekee_answer->set('field_answer_yk', $answer_yks);
    $yeekee_answer->save();
    // --------- d8

    // --------- delete shoot_numbers on mongo
    // $collection = Utils::GetMongoDB()->shoot_numbers;
    // $collection->deleteMany(array('round_id'=>$round_tid));
    // --------- delete shoot_numbers on mongo
    
    // บันทึกผลการออกหวย ยี่กี่ yeekee_answer


    // Loop ให้หวยยีกี่ทั้งหมดเพือทำงาน update status ว่ามีการออกรางวัลเรียบร้อยแล้ว
   
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'chits');
    $query->condition('status', 1);
    $query->condition('field_yeekee_round', $round_tid);
    $query->condition('created', Term::load(31)->get('field_time_answer')->value, '>=');
    $query->condition('created', Term::load($round_tid)->get('field_time_answer')->value, '<=');
    
    $nids = $query->execute();
    if(!empty($nids)){
      $nodes = Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        $node->field_is_award = TRUE;
        $node->save();
      }
    }

    // Loop ให้หวยยีกี่ทั้งหมดเพือทำงาน update status ว่ามีการออกรางวัลเรียบร้อยแล้ว

    $response['result']  = TRUE;  
    return new JsonResponse( $response );
  }

  public function cron_heartbeat(Request $request){
    \Drupal::logger('cron_heartbeat')->notice('Runing.');

    // Utils::autoShootNumber();

    $response['result']  = TRUE;  
    return new JsonResponse( $response );
  }

  public function cron_530AM(Request $request){
    \Drupal::logger('cron_530AM')->notice('Runing.');

    // ลบ document all
    $collection = Utils::GetMongoDB()->shoot_numbers;
    $collection->deleteMany([]);

    // reset time ของรอบหวยยี่กี่
    foreach(\Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('yeekee_round') as $ytag_term) {
      $date = new \DateTime();
      $date->setTime(6, 15*($ytag_term->name - 1), 0);
   
      $term = Term::load($ytag_term->tid);
      if (!empty($term)) {
        $term->field_time_answer = $date->getTimestamp();
        $term->save();
      }
    }
    // reset time ของรอบหวยยี่กี่

    // Utils::mongodb_lotterys_yeekee_rounds();


    Utils::autoShootNumber();

    $response['result']  = TRUE;  
    return new JsonResponse( $response );
  }

  /*
  * ดึงผลการออกรางวัลหวย ยี่กี่ โดยต้องส่ง วันที่และรอบการออกหวย
  * $date : วันที่
  * $round_tid : รอบ
  */
  public function get_yeekee_answer(Request $request /*$date, $round_tid*/ ){
    $time1 = microtime(true);
    if ( Utils::verify($request) ) {
      $content = json_decode( $request->getContent(), TRUE );
      $date                = trim( $content['date'] );
      $round_tid           = trim( $content['round_tid'] );

      if( empty($date) ||  
          empty($round_tid) ){
      
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $time1 = microtime(true);

      $dateTime = new \DateTime();
      $dateTime->setTimestamp($date/1000);

      $pids = \Drupal::entityQuery('paragraph')       
                ->condition('type', 'item_yeekee_answer')
                ->condition('field_round_ye', $round_tid)
                ->condition('field_yeekee_answer_date', /*'2020-07-27'*/ $dateTime->format('Y-m-d') )
                ->execute();

      foreach($pids as $pid) {
        $paragraph  = Paragraph::load( $pid );
        $fid        = $paragraph->get('field_shoot_number_txt')->getValue()[0]['target_id'];
    
        $contents   = Utils::get_fid_cache( $fid );
          
        $p1  = 0;
        $p16 = 0;
        $sum = 0;
        foreach(json_decode($contents) as $i => $value) {
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
        
        // dpm( $sum );
        // dpm( $p1 );
        // dpm( $p16 );

        $response['data']['p1']       = base64_encode(json_encode($p1));  
        $response['data']['p16']      = base64_encode(json_encode($p16));  
        $response['data']['sum']      = base64_encode($sum);  
        $response['data']['contents'] = base64_encode($contents);  
      }

      $response['result']           = TRUE;  
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );

    }else{
      $response['result']   = FALSE;
      return new JsonResponse( $response );
    }
  
    

    /*
    $date = new \DateTime();
    $date->setTimestamp('1595812502');
    // dpm( $date->h );
    // dpm( $date->format('Y-m-d H:i:s') );

    $ymd = explode("-", $date->format("Y-m-d") );

    $y = $ymd[0];
    $m = $ymd[1];
    $d = $ymd[2];
    dpm('Y : ' . $y . ', m : ' . $m . ', d : ' . $d);
    */


    /*
    use Drupal\paragraphs\Entity\Paragraph;
    use Drupal\file\Entity\File;

    $paragraph = Paragraph::load( '1096' );
    $fid = $paragraph->get('field_shoot_number_txt')->getValue()[0]['target_id'] ;

    $file =File::load($fid);
    $path = $file->getFileUri();
    // dpm( $path );

    $field_yeekee_answer_date = $paragraph->get('field_yeekee_answer_date')->getValue();
    dpm( $field_yeekee_answer_date[0]['value'] );
    */

  }
}