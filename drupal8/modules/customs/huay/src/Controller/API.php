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

use Drupal\huay\Utils\Utils;
/**
 * Controller routines for test_api routes.
 * https://www.chapterthree.com/blog/custom-restful-api-drupal-8
 */
class API extends ControllerBase {

  /*
  https://github.com/BoldizArt/D8_Register-Login/blob/master/src/Controller/RegisterLoginController.php
  */
  public function login(Request $request){
    $time1 = microtime(true);

    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
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

          $cookie = $request->headers->get('session');

          $data = array('uid'      =>$uid, 
                        // 'name'     =>$user->getUsername(),
                        // 'email'    =>$user->getEmail(),
                        // 'roles'    =>$user->getRoles(),
                        // 'image_url'=>$image_url,
                        // 'session'  =>\Drupal::service('session')->getId(),
                        // 'cookie'    =>$cookie, //Utils::encode('uid='.$uid."&time=".\Drupal::time()->getCurrentTime()),
                        );

          /*
            กรณีที่ใช้ browser เดียวกัน login ได้หลายๆครั้งจะได้ cookie เดิมเสมอนอกจะ clear cache browser cookie ถึงจะเปลียนแปลง
          */
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
  
          $response['result']           = TRUE;
          $response['execution_time']   = microtime(true) - $time1;

          $response['data']             = $data;
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

    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
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

    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
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

    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
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

    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
      $content = json_decode( $request->getContent(), TRUE );

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;

      $response['data']             = Utils::getTaxonomy_term('list_bank');
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    $response['message']  = 'list_bank';
    return new JsonResponse( $response );
  }

  public function add_bank(Request $request){
    $time1 = microtime(true);

    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
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

      $user = \Drupal\user\Entity\User::load($uid);

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

    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
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
    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
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

  public function add_deposit(Request $request){
    $time1 = microtime(true);
    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
      $content = json_decode( $request->getContent(), TRUE );

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

      $user = \Drupal\user\Entity\User::load($uid);

      $paragraphs = array();
      foreach ($user->get('field_deposit')->getValue() as $ii=>$vv){
          $p = Paragraph::load( $vv['target_id'] );
          $paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      }
      $paragraphs[] = array('target_id'=> $user_deposit->id(), 'target_revision_id' => $user_deposit->getRevisionId());
      
      $user->set('field_deposit', $paragraphs);
      $user->save();

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  /*
    "uid"               : req.body.uid,
    "user_id_bank"      : req.body.user_id_bank,
    "amount_of_withdraw": req.body.amount_of_withdraw,
    "annotation"        : req.body.annotation
  */
  public function withdraw(Request $request){
    $time1 = microtime(true);
    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
      $content = json_decode( $request->getContent(), TRUE );

      $uid                = trim( $content['uid'] );
      $user_id_bank       = trim( $content['user_id_bank'] );       // ID บัญชีธนาคารของลูกค้าที่จะให้โอนเงินเข้า
      $amount_of_withdraw = trim( $content['amount_of_withdraw'] ); // จำนวนเงินที่โอน
      $annotation         = trim( $content['annotation'] );         // ID ธนาคารของเว็บฯ

      if( empty($uid) ||  
          empty($user_id_bank) || 
          empty($amount_of_withdraw) ){
        $response['result']   = FALSE;
        return new JsonResponse( $response );
      }

      $user_withdraw = Paragraph::create([
        'type'                    => 'user_withdraw',
        'field_user_id_bank'      => $user_id_bank,
        'field_amount_of_withdraw'=> $amount_of_withdraw,
        'field_annotation'        => $annotation,
      ]);
      
      $user_withdraw->save();

      $user = \Drupal\user\Entity\User::load($uid);
      $paragraphs = array();
      foreach ($user->get('field_withdraw')->getValue() as $ii=>$vv){
          $p = Paragraph::load( $vv['target_id'] );
          $paragraphs[] = array('target_id'=> $p->id(), 'target_revision_id' => $p->getRevisionId());
      }
      $paragraphs[] = array('target_id'=> $user_withdraw->id(), 'target_revision_id' => $user_withdraw->getRevisionId());
      
      $user->set('field_withdraw', $paragraphs);
      $user->save();

      $response['result']           = TRUE;
      $response['execution_time']   = microtime(true) - $time1;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }
}