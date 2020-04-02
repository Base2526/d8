<?php

namespace Drupal\huay\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Controller routines for page example routes.
 */
class ProfileForm extends FormBase {
//   public $uid;
  public $attachFileDescription = '<div class="attachmentDescription">Allowed extensions: jpg, jpeg, png, pdf <br>Maximum upload file size: 10 MB</div>';
  public $allowFileExtension    = array('jpg jpeg png pdf');
  public $allowFileSize         = array(10 * 1024 * 1024);

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'profile_form';
  }

    /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /*
    $route_match = \Drupal::service('current_route_match');
    $this->uid = $route_match->getParameter('uid');

    if(!empty($this->uid)){
      $user = \Drupal\user\Entity\User::load($this->uid);

      // $uid    = $user->get('uid')->value;
      $name   = $user->get('name')->value;
      $email  = $user->get('mail')->value;

      $cn = $user->get('field_cn')->value;
      $cnth = $user->get('field_cnth')->value;
      $codbranch = $user->get('field_codbranch')->value;

      $roles = array_diff( $user->getRoles(), ['authenticated'] );

      $image_with_preview_fids = array();  
      if (!$user->get('user_picture')->isEmpty()) {
        foreach ($user->get('user_picture')->getValue() as $key => $value){
          $image_with_preview_fids[] = $value['target_id'];
        }
      }
    }
    */

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    dpm(\Drupal::currentUser()->id());

    $form['image_with_preview'] = [
      '#type' => 'managed_file',
      '#title' => t('Picture profile'),
      '#upload_validators' => [
        'file_validate_extensions' => $this->allowFileExtension,
        'file_validate_size' => $this->allowFileSize,
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#upload_location' => 'public://',
      '#required' => FALSE,
      '#default_value' => $image_with_preview_fids,
    ];

    $form['user_ldap'] = array(
      '#type' => 'textfield',
      '#title' => t('User ldap'),
      '#attributes' => array('placeholder' => t('User ldap'), 'readonly' => 'readonly'),
      '#default_value' => $name,
      '#size' => 25,
    );

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => t('Email<div class="requiredField">*</div>'),
      '#attributes' => array('placeholder' => t('Email')),
      '#default_value' => $email,
      '#size' => 25,
    );

    $form['cn'] = array(
      '#type' => 'textfield',
      '#title' => t('CN'),
      '#attributes' => array('placeholder' => t('CN'), 'readonly' => 'readonly'),
      '#default_value' => $cn,
      '#size' => 25,
    );

    $form['cnth'] = array(
      '#type' => 'textfield',
      '#title' => t('CN TH'),
      '#attributes' => array('placeholder' => t('CN TH')),
      '#default_value' => $cnth,
      '#size' => 25,
    );

    if(!empty($cnth)){
      $form['cnth']['#attributes'] =  array_merge($form['cnth']['#attributes'], array('readonly' => 'readonly'));
    }
    
    $form['codbranch'] = array(
      '#type' => 'textfield',
      '#title' => t('Code Branch'),
      '#attributes' => array('placeholder' => t('Code Branch'), 'readonly' => 'readonly'),
      '#default_value' => $codbranch,
      '#size' => 25,
    );

    $form['roles'] = array(
      '#type' => 'textfield',
      '#title' => t('Roles'),
      '#attributes' => array('placeholder' => t('Roles'), 'readonly' => 'readonly'),
      '#default_value' => implode(", ",$roles),
      '#size' => 25,
    );

    $form['send'] = array(
        '#type' => 'submit',
        '#name' => 'login',
        '#value' => $this->t('Save'),
        '#prefix' => '<div class="csaProfileFormSubmitZone">',
        '#suffix' => '</div>'
    );

    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      parent::validateForm($form, $form_state);

      $email = $form_state->getValue('email');
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $form_state->setErrorByName('email', $this->t('กรุณากรอก อีเมลล์ OR Invalid email format.'));
      }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $session = \Drupal::request()->getSession();
    // $field= $form_state->getValues();

    // $user = \Drupal\user\Entity\User::load($this->uid);
    // $user->set('mail', $field['email']);
    // $user->set('field_cnth', $field['cnth']);
    
    // $image_with_preview = $form_state->getValue('image_with_preview');
    // if(!empty($image_with_preview)){
    //   $user->set('user_picture', $image_with_preview[0]);
    // }else{
    //   $user->set('user_picture', '');
    // }
    // $user->save();
    // drupal_set_message("Update user succesfully.");
  }
}