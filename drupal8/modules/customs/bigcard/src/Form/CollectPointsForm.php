<?php

/**
 * @file
 * Contains \Drupal\demo\Form\Multistep\MultistepTwoForm.
 */

namespace Drupal\bigcard\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\config_pages\Entity\ConfigPages;

use Drupal\bigcard\Utils\Utils;

class CollectPointsForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'collect_points_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        // $node = Node::load(4);
        // $body  = $node->body->value;
        // if(!empty( $body )){
        //     $form['text'] = array(
        //         '#type'     =>'markup',
        //         '#markup'   => $body ,             
        //     );
        // }

        // $img_target_id = $node->field_image->target_id;
        // if(!empty($img_target_id)){
        //     $form['img'] = array(
        //         '#type'     =>'markup',
        //         '#markup'   =>'<img width="100%" style="border: 1px #ccc solid;margin: 10px;" src="'.Utils::get_file_url( $img_target_id ).'" alt="picture">',             
        //     );
        // }

        $cumulative_exchange_points = ConfigPages::config('cumulative_exchange_points');
        $content = $cumulative_exchange_points->get('field_collect_points_content')->getValue()[0]['value'];

        // dpm($content);
        if(!empty($content)){
            $form['content'] = array(
                '#type'     =>'markup',
                '#markup'   => $content,             
            );
        }
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
