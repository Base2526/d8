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

class SpecialPrivilegesDetailForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'special_privileges_detail_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $route_match = \Drupal::service('current_route_match');
        $nid = $route_match->getParameter('nid');

        $node = Node::load($nid);
        if(empty($node)){

        }

        $img_path = '';                
        $im = $node->get('field_image');
        if(!empty($im->getValue())){ 
          $img_path = Utils::get_file_url($im->getValue()[0]['target_id']); 
        }

        $array_data = array();
        $array_data = [
            'img_path'    => $img_path,
            'link'        => '',
            'title'       => '',
            'article'     => $node->label(),
            'detail'      => 'ร้านอาหาร คาเฟ่',
            'start_date'  =>  Utils::mapping_date(date( "Y-m-d", ($node->get('field_duration_from')->date->getTimestamp())), \Drupal::languageManager()->getCurrentLanguage()->getId()),
            'end_date'    =>  Utils::mapping_date(date( "Y-m-d", ($node->get('field_duration_to')->date->getTimestamp())), \Drupal::languageManager()->getCurrentLanguage()->getId()),
            'score'       => '200',
            'privilege'   => 'ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
            'condition'   => [
                                'สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที',
                                'ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%',
                                '1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc',
                                'ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์',
                                'ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี',
                                'เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้',
                                'ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                             ],
          'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
          'redeem-mobile'  => '*783*45*850*',
          'more_info'      => $node->get('body')->getValue()[0]['value'],
          'branch'         => [
                                'บิ๊กซี เชียงใหม่ 2',
                                'บิ๊กซี บางพลี',
                                'บิ๊กซี นครปฐม',
                                'บิ๊กซี แจ้งวัฒนะ 2',
                                'บิ๊กซี แจ้งวัฒนะ',
                                'บิ๊กซี ลพบุรี',
                                'บิ๊กซี ลพบุรี 2',
                                'บิ๊กซี อ่างทอง',
                                'บิ๊กซี อยุธยา',
                                'บิ๊กซี ราชดำริ',
                                'บิ๊กซี สุขสวัสดิ์',
                                'บิ๊กซี เพชรเกษม 2',
                                'บิ๊กซี ราษฏร์บูรณะ',
                                'บิ๊กซี เพชรเกษม67',
                                'บิ๊กซี อ้อมใหญ่',
                              ],
        ];   

        $str_condition = '';
        $count = 1;
        foreach($array_data['condition'] as $condition)
        {
            $str_condition = $str_condition .
            '<span style="font-size: large;">'.$count.'. '.$condition.'</span><br>';
            $count++;
        }

        $str_branch = '';
        foreach($array_data['branch'] as $branch)
        {
            $str_branch = $str_branch .
            '<span style="font-size: large;">
                <strong>'.$branch.'</strong>
             </span><br>';
        }

        $form['div_pre'] = array(
            '#type'     => 'markup',
            '#markup'   => ' <div class="main-wrapper">
            <div class="row">',
            '#prefix'   => '',
            '#suffix'   => ''
        );

        $form['pic_head'] = array(
            '#type'     => 'markup',
            '#markup'   => '<div class="col-lg-7 col-md-7 col-sm-12 col-12 banner_area_cate">

            <div class="banner_img">
                <div class="more-views" style="wdith:694px;">
                    <img src="'.$array_data['img_path'].'" alt="'.$array_data['title'].'">
                </div>
            </div>

            <div class="share-product">
                <b><label>Share: </label></b>
                <ul class="sharing-links">
                    <li>
                        <a href="'.$array_data['facebook_share'].'" target="_blank" title="Share on Facebook" class="link-facebook">
                            Share Facebook        
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-detail">
                <div class="title" style="font-size: 24px;">ช่องทางแลกคะแนน</div>
                <ul class="redeem-points">
                    <li class="redeem-mobile">
                        <img style="width:60px" src="/sites/default/modules/customs/bigcard/images/image-redeem-mobile-2x.png" alt="redeem-mobile">
                    </li>
                </ul>
                <div class="ponit_ussd">
                            <p><span style="font-size: large;"><strong>'.$array_data['redeem-mobile'].' บัตรประชาชน 13 หลัก# โทรออก<span class="font_red">&nbsp;(ฟรี)</span></strong></span></p>
                </div>
            </div>
          
</div>',
            '#prefix'   => '',
            '#suffix'   => ''
          );

        // rate
        // <li>
        //   <div class="show_star">
        //       <div class="ratings">
        //           <div class="rating-box">
        //               <div class="rating">&nbsp;</div>
        //           </div>
        //       </div>
        //   </div>
        // </li>

          $form['detail'] = array(
            '#type'     => 'markup',
            '#markup'   => '<div class="col-lg-5 col-md-5 col-sm-12 col-12 view_detail_area">
            <div class="view_detail">
                <div class="title_detail"><h2>'.$array_data['article'].'</h2></div>
                <div class="after_title">
                    <div class="right_show">
                        <ul class="list-unstyled">
                            <li class="product-period">
                                <span>'.$array_data['start_date'].' - '.$array_data['end_date'].'</span>
                            </li>
                            <li class="print_text">
                            |
                            พิมพ์
                            <a class="print_thispage"></a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                    
                <div class="clearfix"></div>

                <div class="view_detail_show clearfix">
                    <div class="show_view_detail clearfix">
                        <div class="in_box_set_attribute std-list">
                            <div class="title_view">สิทธิพิเศษ</div>
                            <p><span style="font-size: x-large;"><strong><span class="font_red">คะแนนบิ๊กการ์ด '.$array_data['score'].' คะแนน</span>
                            &nbsp;แลกรับ</strong></span><br><span style="font-size: x-large;"><strong>'.$array_data['privilege'].'</strong></span></p>                                
                        </div>
                            
                        <div class="in_box_set_attribute std-list">
                            <div class="title_view">เงื่อนไขการรับสิทธิ</div>
                            <p class="font_gray">
                            <span style="font-size: large;">
                            '.$str_condition.'                       
                        </div>
 
                        <div class="line_show clearfix">
                            <div class="title_view">ระยะเวลา</div>
                            <p class="font_gray"><span class="answer">'.$array_data['start_date'].' - '.$array_data['end_date'].'</span></p>                             </span>
                        </div>
                                
                        <div class="title_view">ข้อมูลเพิ่มเติม</div>

                            
                        <p></p><p class="font_gray"><span style="font-size: large;">'.$array_data['more_info'].'</span></p><p></p>
                           
                        <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="store_contact clearfix">
                            <h3>ข้อมูลที่ติดต่อร้านค้า</h3>
                            <span class="branch_list">
                                <p>
                                '.$str_branch.'
                                </p>                                        
                                <div class="more">ดูทั้งหมด...</div>
                            </span>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>',
            '#prefix'   => '',
            '#suffix'   => ''
          );

          $form['div_post'] = array(
            '#type'     => 'markup',
            '#markup'   => '</div></div><p>&nbsp;</p>',
            '#prefix'   => '',
            '#suffix'   => ''
        );

        // $form['hello'] = array(
        //     '#type'     => 'item',
        //     '#markup'   => '',
        //     '#prefix'   => '<div class="row"><p>&nbsp;</p></div><div class="row"><p>&nbsp;</p></div><div class="row"><p>&nbsp;</p></div><div class="row"><p>&nbsp;</p></div><div class="row"><p>&nbsp;</p></div>',
        //     '#suffix'   => ''
        //   );

        // $form['print_preview'] = array(
        //     '#type'    => 'item',
        //     '#markup'  => '<div class="print-view">
          
        //     <div class="print-promotion">
        //       <div class="pp-head">
        //         <span class="left">07/06/2020</span>
        //         <span class="right">Dairy Queen</span>
        //       </div>
        //       <div class="pp-main">
        //         <div class="pp-logo">
        //           <img alt="BigCard Promotion" src="./Dairy Queen - อาหาร &amp; เครื่องดื่ม_files/p-logo.png">
        //         </div>
        //         <div class="pp-content">
        //           <div class="pp-promotion">
        //                      <img src="./Dairy Queen - อาหาร &amp; เครื่องดื่ม_files/big-deal_inside-banner-420x690px_dairy-queen(1).jpg" alt="Dairy Queen">
        //                   </div>
        //                 <div class="pp-textform">
        //             <h3>สิทธิพิเศษ</h3>
        //             <div class="ppd-left"><p><span style="font-size: x-large;"><strong><span style="color: #ff0000;">คะแนนบิ๊กการ์ด 100 คะแนน</span>&nbsp;แลกรับ</strong></span><br><span style="font-size: x-large;"><strong>ฟรี วาฟเฟิล โคน บลิซซาร์ด 1 โคน มูลค่า 45 บาท (รสชาติโอริโอ, สตรอเบอร์รี่, บราวนี่ ช็อกโกแลต)</strong></span></p></div>
        //           </div>
        //                       <div class="pp-textform">
        //             <h3>เงื่อนไขการรับสิทธิ</h3>
        //             <div class="ppd-left"><p><span style="font-size: large;">1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 100 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที</span><br><span style="font-size: large;">2. 1 รหัส/สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือแสดงรหัสที่หน้าร้านเฉพาะสาขาที่ร่วมรายการเท่านั้น</span><br><span style="font-size: large;">3. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์</span><br><span style="font-size: large;">4. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิ์ไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี</span><br><span style="font-size: large;">5. ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือ โปรโมชั่นอื่นได้</span><br><span style="font-size: large;">6. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆจะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด</span></p></div>
        //           </div>
        //                 <div class="pp-textform">
        //             <h3>ระยะเวลา</h3>
        //                     <p>
        //               </p></div></div></div></div></div></div>',
        // );

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
