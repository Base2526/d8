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

/**
 * Controller routines for test_api routes.
 * https://www.chapterthree.com/blog/custom-restful-api-drupal-8
 */
class API extends ControllerBase {

  /*
  https://github.com/BoldizArt/D8_Register-Login/blob/master/src/Controller/RegisterLoginController.php
  */
  public function login(Request $request){
    if (strcmp( $request->headers->get('Content-Type'), 'application/json' ) === 0 ) {
      $content = json_decode( $request->getContent(), TRUE );

      $name = trim( $content['params']['name']);
      $pass = trim( $content['params']['pass'] );
  
      if(!(( empty($name) && empty($pass) ) ||
           empty($name) ||
           empty($pass)
        )){
          
        /*
        * case is email with use user_load_by_mail reture name
        */
        if(\Drupal::service('email.validator')->isValid( $name )){
          $name = user_load_by_mail($name)->getUsername();
        }
  
        $uid = \Drupal::service('user.auth')->authenticate($name, $pass);
        if(!empty($uid)){
          $user = User::load($uid);
          $user_login_finalize = user_login_finalize($user);
          
          $image_url = '';  
          if (!$user->get('user_picture')->isEmpty()) {
            $image_url = file_create_url($user->get('user_picture')->entity->getFileUri());
          }

          $data = array('uid'      =>$uid, 
                        'name'     =>$user->getUsername(),
                        'email'    =>$user->getEmail(),
                        'roles'    =>$user->getRoles(),
                        'image_url'=>$image_url 
                        );
  
          $response['result'] = TRUE;
          $response['data']   = $data;
          return new JsonResponse( $response );
        }
      } 
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

  public function register(){
    $name = trim( $_POST['name'] );
    $pass = trim( $_POST['pass'] );

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
      $response['user']      = $user;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
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
  public function reset_password(){
    $name = trim( $_POST['name'] );

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
    $response['message']  = t('@id | @name |  @email', array('@id'=>$user->id(), '@name' => $user->getUsername(), '@email' => $user->getEmail()))->__toString();
    return new JsonResponse( $response );
  }
}