<?php

namespace Drupal\huay\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;
// use Drupal\credit_sales_approval\Controller\MainPageController;

/**
 * Controller routines for page example routes.
 */
class RequestForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'request_form';
  }

    /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    dpm(rand());
    $form['something'] = array(
        '#type' => 'item',
        '#prefix' => '<ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item active">
                          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                            aria-selected="true">Home</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                            aria-selected="false">Profile</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact"
                            aria-selected="false">Contact</a>
                        </li>',
        '#suffix'   => '</ul>
        '
      );

      $form['something2'] = array(
        '#type' => 'item',
        '#prefix' => '<div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active in" id="home" role="tabpanel" aria-labelledby="home-tab">Raw denim you
          probably haven\'t heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master
          cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro
          keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip
          placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi
          qui.</div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Food truck fixie
          locavore, accusamus mcsweeney\'s marfa nulla single-origin coffee squid. Exercitation +1 labore velit,
          blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee.
          Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum
          PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS
          salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit,
          sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester
          stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Etsy mixtape
          wayfarers, ethical wes anderson tofu before they sold out mcsweeney\'s organic lomo retro fanny pack
          lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard
          locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify
          squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie
          etsy retro mlkshk vice blog. Scenester cred you probably haven\'t heard of them, vinyl craft beer blog
          stumptown. Pitchfork sustainable tofu synth chambray yr.</div>
      ',
        '#suffix'   => '</div>'
      );
    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      parent::validateForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
 
  }
}