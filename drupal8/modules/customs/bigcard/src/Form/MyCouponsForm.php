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
use Drupal\bigcard\Utils\Utils;


class MyCouponsForm extends FormBase {

    private $data_my_coupon;

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'my_coupons_form';
    }

    /**
     * {@inheritdoc}.
    */
    public function __construct() {
        $this->data_my_coupon = Utils::my_coupon_api();
    }

    public function get_bigc_coupon($data_my_coupon){
        $bigc_coupon_arr = array();
        foreach ($data_my_coupon as $coupon_item){
            if($coupon_item['couponType'] == 'DM'){
                array_push($bigc_coupon_arr, $coupon_item);
            }
        }
        return $bigc_coupon_arr;
    }

    public function get_shop_coupon($data_my_coupon){
        $shop_coupon_arr = array();
        foreach ($data_my_coupon as $coupon_item){
            if($coupon_item['couponType'] == 'ET'){
                array_push($shop_coupon_arr, $coupon_item);
            }
        }
        return $shop_coupon_arr;
    }

    public function get_coupon_card($coupon_arr){
        $result = '<div class="row">';
    
        foreach ($coupon_arr as $coupon_item){

            // dpm( $coupon_item );

            $coupon_image = empty( $coupon_item['myCouponImage'] ) ? '/sites/default/modules/customs/bigcard/images/not-found.png' : $coupon_item['myCouponImage'];
            $result .= '<div class="col-lg-6 col-md-6 col-sm-12 col-12">' .
                        '<a href="../coupon_detail/' . $coupon_item["couponId"] .'&' .(empty($coupon_item["is_ocp"]) ? '0' : '1' ) . '"><div class="coupon-card">' .
                            '<img class="coupon-card-img" src="' . $coupon_image . '">' .
                            '<div class="coupon-card-tag">couponId : ' . $coupon_item["couponId"] . 
                                '<br>type : ' . $coupon_item["couponType"] .
                                // ' status : ' . $coupon_item["couponStatus"] .
                                '<br>couponTimeout : ' . $coupon_item["couponTimeout"] .
                                '<br>date : ' . $coupon_item["startDate"] . '-' . $coupon_item["endDate"] .
                            '</div>' . (( strcasecmp($coupon_item["couponStatus"], 'u') == 0 ) ? '<div>ใช้แล้ว</div>' : '')  .
                        '</div></a>' . 
                        '<div class="coupon-card-name">' . $coupon_item["couponName"] . '</div>'.
                        '</div>';
        }

        $result .= '</div>';
        return $result;
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // $form['container']['personal_information']['sex'] = array(
        //     '#type'         => 'radios',
        //     '#default_value'=> 0,
        //     '#options'      => ['คูปองบิ๊กซี', 'คูปองร้านค้า'],
        // );

        // $element[$delta]['#options']['attributes']['class'] = $settings['style'];

        // $form['something'] = array(
        //     '#type' => 'select',
        //     '#title' => 'whatever',
        //     // '#options' => array(
        //     //   1=>'yes',
        //     //   2=>'no',
        //     // ),
        //     // // '#options' => array(
        //     // //     array('#value' => 'text 1', '#attributes' => array('class' => 'background: orange;', )),
        //     // //     array('#value' => 'text 1', '#attributes' => array('class' => 'background: red;', )),
        //     // // ),
        //     // '#attributes' => array('class' => array('background: orange;')),

        //     '#options' => array(
        //         'AL' => t('Alabama'),
        //         'AK' => t('Alaska'),
        //         'AZ' => t('Arizona'),
        //         'AR' => t('Arkansas'),
        //         // ..
        //         'WI' => t('Wisconsin'),
        //         'WY' => t('Wyoming'),
        //       ),
        //       '#options_attributes' => array(
        //         'AL' => array('class' => array('side-bar-li'), 'data-bbq-meat' => 'pork'),
        //         'AK' => array('class' => array('non-contiguous'), 'data-bbq-meat' => 'salmon'),
        //         'AZ' => array('class' => array('southwest'), 'data-bbq-meat' => 'rattlesnake'),
        //         'AR' => array('class' => array('south'), 'data-bbq-meat' => 'beef'),
        //         // ...
        //         'WI' => array('class' => array('midwest'), 'data-bbq-meat' => 'cheese'),
        //         'WY' => array('class' => array('flyover'), 'data-bbq-meat' => 'bison'),
        //         ),
        //       '#attributes' => array('class' => array('states-bbq-selector')),
        //   );

        ////
        // $data_my_coupon   = $form_state->get('data_my_coupon');
        // if(empty($data_my_coupon)){
        //     $data_my_coupon = Utils::my_coupon_api();

        //     $form_state->set('data_my_coupon', $data_my_coupon );
        // }

        // $bigc_coupon_arr = $this->get_bigc_coupon($data_my_coupon['data']);
        // $shop_coupon_arr = $this->get_shop_coupon($data_my_coupon['data']);

        // dpm($this->data_my_coupon);
        // dpm($bigc_coupon_arr);
        // dpm($shop_coupon_arr);
        // $aaa = $this->get_coupon_card($bigc_coupon_arr);
        // dpm($aaa);

        $form['container']['header'] = array(
            '#type' => 'markup',
            '#markup' => '<div class="row">
                            <div class="col-12 py-2 fs-xl fw-400">
                                <i class="fas fa-tag fs-md fw-600"></i>  คูปองของฉัน
                            </div>
                            <div class="line-bottom"></div>
                        </div>'
        );

        // $form['container']['coupon_tab'] = array(
        //     '#type' => 'radios',
        //     '#title' => t(''),
        //     /*'#description' => t('Select a method for deleting annotations.'),*/
        //     '#options' => array('0' => 'คูปองบิ๊กซี', '1' => 'คูปองร้านค้า'),
        //     '#default_value' => '0',
        //     '#required' => TRUE,
        // );

        
        $form['container']['coupon_tab'] = array(
            '#type'     => 'markup',
            '#markup'   => '<div class="py-3">
                                <!-- Nav pills -->
                                <ul class="nav nav-pills" role="tablist">
                                    <li id="coupon-bigc-tab" class="nav-item activeTab">
                                        <a class="active" data-toggle="pill" href="#coupon-bigc">คูปองบิ๊กซี</a>
                                    </li>
                                    <li id="coupon-shop-tab" class="nav-item">
                                        <a class="" data-toggle="pill" href="#coupon-shop">คูปองร้านค้า</a>
                                    </li>
                                </ul>
                            
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div id="coupon-bigc" class="container tab-pane active"><br>
                                        <h3>คูปองบิ๊กซี</h3>
                                       ' . $this->get_coupon_card( $this->get_bigc_coupon($this->data_my_coupon['data']) ) .'
                                    </div>
                                    <div id="coupon-shop" class="container tab-pane fade"><br>
                                        <h3>คูปองร้านค้า</h3>
                                        ' . $this->get_coupon_card( $this->get_shop_coupon($this->data_my_coupon['data']) ) .'
                                    </div>
                                </div>
                            </div>',
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
