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
    $name = trim( $_POST['name'] );
    $pass = trim( $_POST['pass'] );

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
        user_login_finalize($user);

        $response['result']   = TRUE;
        $response['user']      = $user;
        return new JsonResponse( $response );
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
      // $user->set("langcode", \Drupal::languageManager()->getCurrentLanguage()->getId());
      $user->enforceIsNew();
      $user->setEmail($name);
      $user->setUsername(explode("@", $name)[0]);
      
      // Optional settings
      $user->activate();

      // Save user
      $user->save();

      // User login
      user_login_finalize($user);

      $response['result']   = TRUE;
      $response['user']      = $user;
      return new JsonResponse( $response );
    }

    $response['result']   = FALSE;
    return new JsonResponse( $response );
  }

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

    $response['result']   = TRUE;
    $response['message']  = t('@name |  @email', array('@name' => $user->getUsername(), '@email' => $user->getEmail()))->__toString();
    return new JsonResponse( $response );
  }
}