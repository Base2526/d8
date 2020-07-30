<?php

/**
 * @file
 * Contains \Drupal\demo\Form\Multistep\MultistepFormBase.
 */

namespace Drupal\bigcard\Form\MultistepNewMember;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Gregwar\Captcha\CaptchaBuilder;

abstract class MultistepFormBase extends FormBase {

  /**
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * @var \Drupal\user\PrivateTempStore
   */
  protected $store;

  /**
   * CaptchaBuilder
  */
  protected $captchaBuilder;

  /**
   * Constructs a \Drupal\demo\Form\Multistep\MultistepFormBase.
   *
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   * @param \Drupal\Core\Session\AccountInterface $current_user
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->sessionManager = $session_manager;
    $this->currentUser = $current_user;

    $this->store = $this->tempStoreFactory->get('multistep_data');

    $this->captchaBuilder = new CaptchaBuilder;
    
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user.private_tempstore'),
      $container->get('session_manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Start a manual session for anonymous users.
    if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
      $_SESSION['multistep_form_holds_session'] = true;
      $this->sessionManager->start();
    }

    // $form = array();
    // $form['actions']['#type'] = 'actions';
    // $form['actions']['submit'] = array(
    //   '#type' => 'submit',
    //   '#value' => $this->t('Submit'),
    //   '#button_type' => 'primary',
    //   '#weight' => 10,
    // );

   

    return $form;
  }

  /**
   * Saves the data from the multistep form.
   */
  protected function saveData() {
    // Logic for saving data.
    $this->deleteStore([]);
    drupal_set_message($this->t('The form has been saved.'));
  }

  /**
   * Helper method that removes all the keys from the store collection used for
   * the multistep form.
   */
  protected function deleteStore($except_array) {
    dpm('in delete store');
    // $keys = ['name', 'email', 'age', 'location'];
    $keys = [ 
    'select_type', 
    'id_card', 
    'passport', 
    'data_check_exists_id_card', 
    'data_find_member', 
    'dial_code', 
    'tel', 
    'full_tel',
    'otp_number', 
    'data_send_otp',
    'data_validate_store', 
    'data_check_card', 
    'data_check_exists_welfare_id', 
    'issued_store', 
    'welfare_id', 
    'title', 
    'name', 
    'last_name',
    'gender', 
    'occupation', 
    'birth_date', 
    'email', 
    'home_phone', 
    'home_phone_ext', 
    'add_type',
    'country',
    'address1', 
    'room_no', 
    'address2',
    'moo', 
    'soi', 
    'road', 
    'province', 
    'district', 
    'sub_district', 
    'postal_code', 
    'city_foreigner',
    'province_foreigner',
    'postal_code_foreigner',
    'language', 
    'agree',
    'password', 
    'confirm_password', 
    'captcha', 
    'is_valid_captcha', 
    'data_register', 
    'data_sms_new_member',
    'data_sms_welcome_pack',
    'type'
    ];

    foreach ($keys as $key) {
      if(in_array($except_array)){

      }else{
        $this->store->delete($key);
      }
    }
  }
}
