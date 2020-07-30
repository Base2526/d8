<?php
namespace Drupal\bigcard\Plugin\Page;

use Drupal\Core\Controller\ControllerBase;

use Drupal\config_pages\Entity\ConfigPages;
use Drupal\paragraphs\Entity\Paragraph;

use Drupal\node\Entity\Node;
use Drupal\bigcard\Utils\Utils;

/**
 * Controller routines for page example routes.
 */
class FrontPage extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'front_page';
  }

  /*
    Sale
      - Reopen
      - Draft
      - Inprogress
      - Done
    
    B2B
      - Reopen
      - New task
      - Draft
      - Approved
    
    BJC
      - New task
      - Disapproved
      - Approved
  */

  // ----- Document status list -----
  // New: 17
  // Inprogress: 18
  // Pending approval: 19
  // Approved : 20
  // Done: 21
  // Reopen to B2B	: 22
  // Reopen to Sales account : 23

  public function page() {
    $params = array();
    /*
    switch(MainPageController::userRole()){
      case 'sale':{
        $nids = \Drupal::entityQuery('node')
                ->condition('type','new_customer')
                ->condition('uid',  \Drupal::currentUser()->id())
                ->condition('status', 1)
                ->execute();

        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

        // dpm(count($nodes));
        $reopen = 0;
        $draft = 0;
        $inprogress = 0;
        $done = 0;
        foreach( $nodes as $node ) {
          $document_status  = $node->get('field_document_status')->target_id;

          switch($document_status){
            // Reopen: 23
            case 23:{
              $reopen++;
            break;
            }

            // Draft: 17
            case 17:{
              $draft++;
            break;
            }

            // Inprogress: 18
            case 18:{
              $inprogress++;
            break;
            }

            // Done: 21
            case 21:{
              $done++;
            break;
            }
          }
        }

        $params = array('Reopen'=>$reopen, 'Draft'=>$draft, 'Inprogress'=>$inprogress, 'Done'=>$done);
      break;
      }
      case 'b2b':{
        // $nids = \Drupal::entityQuery('node')
        //         ->condition('type','new_customer')
        //         // ->condition('uid',  \Drupal::currentUser()->id())
        //         ->condition('status', 1)
        //         ->condition('field_document_status', 18)
        //         ->execute();


        $query = \Drupal::entityQuery('node')
                ->condition('type','new_customer')
                // ->condition('uid',  \Drupal::currentUser()->id())
                ->condition('status', 1);
                // ->condition('field_document_status', 18);

                $group2= $query->andConditionGroup()
                ->condition('uid',  \Drupal::currentUser()->id())
                ->condition('field_document_status', 17);

                $group1= $query->orConditionGroup()
                ->condition($group2)
                ->condition('field_document_status', 22)
                ->condition('field_document_status', 18)
                // ->condition('field_document_status', 17)
                ->condition('field_document_status', 20);

                
        $nids = $query->condition($group1)->execute();
        // $query = \Drupal::entityQuery('node');
        // $query->condition('status', \Drupal\node\NodeInterface::PUBLISHED);
        // $query->condition('type', 'new_customer');
        // $group1= $query->andConditionGroup()
        // ->condition('uid', \Drupal::currentUser()->id())
        // ->condition('field_document_status', 18);
        // $group2 = $query->orConditionGroup()
        // ->condition($group1)
        // ->condition('field_document_status', 18);
        // // ->condition('field_document_status', 19)
        // // ->condition('field_document_status', 20)
        // // ->condition('field_document_status', 22)
        // // ->condition('field_document_status', 23);
        // $nids = $query->condition($group2)->execute();

        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

        // dpm(count($nodes));
        $reopen = 0;
        $new_task = 0;
        $draft = 0;
        $approved = 0;
        foreach( $nodes as $node ) {
          $document_status  = $node->get('field_document_status')->target_id;
          switch($document_status){
            // Reopen: 22
            case 22:{
              $reopen++;
            break;
            }

            // New task: 18
            case 18:{
              $new_task++;
            break;
            }

            // Draft: 17
            case 17:{
              $draft++;
            break;
            }

            // Approved: 20
            case 20:{
              $approved++;
            break;
            }
          }
        }

        $params = array('Reopen'=>$reopen, 'New task'=>$new_task, 'Draft'=>$draft, 'Approved'=>$approved);
      break;
      }
      case 'bjc':{
        $nids = \Drupal::entityQuery('node')
                ->condition('type','new_customer')
                // ->condition('uid',  \Drupal::currentUser()->id())
                ->condition('field_type_customer', 4, '!=')
                ->condition('status', 1)
                ->execute();

        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

        $new_task = 0;
        $disapproved = 0;
        $approved = 0;
        foreach( $nodes as $node ) {
          $document_status  = $node->get('field_document_status')->target_id;

          switch($document_status){
            // New task: 20
            case 19:{
              $new_task++;
            break;
            }

            // Disapproved: 22
            case 22:{
              $disapproved++;
            break;
            }

            // Approved: 21
            case 20:{
              $approved++;
            break;
            }
          }
        }

        $params = array('New task'=>$new_task, 'Disapproved'=>$disapproved, 'Approved'=>$approved);
      break;
      }
    }
    */

    // $slide = array( 's'=>array(array('url'=>'url'), array('url'=>'url'), array('url'=>'url')),
    //                 'l'=>array('v'=>'url', 'h'=>'url')) ;

    // $data = array('SPECIAL_PROMOTION'=> array( array('title'=>'test 1', 'image_url'=>'www.url.com/image'),
    //                                     array('title'=>'test 2', 'image_url'=>'www.url.com/image2'),
    //               'FOOD'=>array( array('title'=>'test 1', 'image_url'=>'www.url.com/image'),
    //                       array('title'=>'test 2', 'image_url'=>'www.url.com/image2') );
    
    /*
    $slid_main = 
    [
        0 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/bigcard-kakao-banner-web-890x430px-01.jpg',
              'title'    => 'Bigcard Exprice',
              'link'     => 'https://bigcard.bigc.co.th/register-1200',
            ],
        1 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/bigcard-exprie-slide-banner-web-final.jpg',
              'title'    => 'ช่องทางตรวจสอบคะแนน',
              'link'     => 'https://bigcard.bigc.co.th/register-1200',
            ],
        2 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/bigcard-clearance-sale-banner-web-890x430px-01.jpg',
              'title'    => 'ช่องทางตรวจสอบคะแนน',
              'link'     => 'https://bigcard.bigc.co.th/register-1200',
            ],
    ];
    */
    

    
    $slide_front_page = ConfigPages::config('slide_front_page');

    $slid_main = array();
    foreach ($slide_front_page ->get('field_slide_main')->getValue() as $i => $v){
      $p = Paragraph::load( $v['target_id'] );
      $link = $p->get('field_item_slide_link')->getValue()[0]['value'];
      $title = $p->get('field_item_slide_title')->getValue()[0]['value'];

      $url = '';                
      $im = $p->get('field_item_slide_image');
      if(!empty($im->getValue())){ 
        $url = Utils::get_file_url($im->getValue()[0]['target_id']); 
      }
      $slid_main[] = array('title'=>$title, 'link'=>$link, 'img_path'=>$url);
    }
    
    $main_r_0_title = $slide_front_page->get('field_main_r_0_title')->getValue()[0]['value'];
    $main_r_0_link  = $slide_front_page->get('field_main_r_0_link')->getValue()[0]['value'];
    $main_r_0_img   = Utils::get_file_url($slide_front_page->get('field_main_r_0_img')->getValue()[0]['target_id']); 

    $main_r_1_title = $slide_front_page->get('field_main_r_1_title')->getValue()[0]['value'];
    $main_r_1_link  = $slide_front_page->get('field_main_r_1_link')->getValue()[0]['value'];
    $main_r_1_img   = Utils::get_file_url($slide_front_page->get('field_main_r_1_img')->getValue()[0]['target_id']); 
    
    $main_r = 
    [
        0 => ['img_path' => $main_r_0_img,
              'title'    => $main_r_0_title,
              'link'     => $main_r_0_link,
              'type'     => 'mobile',
             ],
        1 => ['img_path' => $main_r_1_img,
              'title'    => $main_r_1_title,
              'link'     => $main_r_1_link,
              'type'     => 'desktop',
             ],
    ];
    

     /*
    $main_r = 
    [
        0 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/banner-website-mobile-final-01-00.jpg',
              'title'    => 'Bigcard Exprice',
              'link'     => 'https://bigcard.bigc.co.th/register-1200',
              'type'     => 'mobile',
             ],
        1 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/bigcard-290x430px-final-01.jpg',
              'title'    => 'เธเนเธญเธเธ—เธฒเธเธ•เธฃเธงเธเธชเธญเธเธเธฐเนเธเธ',
              'link'     => 'https://bigcard.bigc.co.th/register-1200',
              'type'     => 'desktop',
             ],
    ];
    
   
    $category_sp_menu =  
    [
      0 => ['img_path' => 'https://bigcard.bigc.co.th/media/catalog/category/icon-food-drink2x_2.png',
            'title'    => 'ช่องทางตรวจสอบคะแนน',
            'link'     => 'https://bigcard.bigc.co.th/register-1200',
            'text'     =>  'อาหาร & เครื่องดื่ม',
           ],
      1 => ['img_path' => 'https://bigcard.bigc.co.th/media/catalog/category/icon-food-drink2x_2.png',
            'title'    => 'ช่องทางตรวจสอบคะแนน',
            'link'     => 'https://bigcard.bigc.co.th/register-1200',
            'text'     => 'โรงแรม ที่พัก & ท่องเที่ยว',
           ],
      2 => ['img_path' => 'https://bigcard.bigc.co.th/media/catalog/category/icon-food-drink2x_2.png',
            'title'    => 'ช่องทางตรวจสอบคะแนน',
            'link'     => 'https://bigcard.bigc.co.th/register-1200',
            'text'     =>  'สุขภาพ & ความงาม',
           ],
      3 => ['img_path' => 'https://bigcard.bigc.co.th/media/catalog/category/icon-food-drink2x_2.png',
           'title'    => 'ช่องทางตรวจสอบคะแนน',
           'link'     => 'https://bigcard.bigc.co.th/register-1200',
           'text'     =>  'อาหาร & เครื่องดืม',
          ],
      4 => ['img_path' => 'https://bigcard.bigc.co.th/media/catalog/category/icon-food-drink2x_2.png',
          'title'    => 'ช่องทางตรวจสอบคะแนน',
          'link'     => 'https://bigcard.bigc.co.th/register-1200',
          'text'     =>  'อาหาร & เครื่องดืม',
          ],
    ];
    */
    

    
    $privilege_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('privilege');
    $category_sp_menu = array();
    foreach($privilege_terms as $tag_term) {
      $link = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag_term->tid)->get('field_link')->getValue()[0]['value'];
      $img_path = Utils::get_file_url( \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tag_term->tid)->get('field_img_icon')->getValue()[0]['target_id'] );

      $category_sp_menu[] = array('text'=> $tag_term->name, 
                                  'title'=>strip_tags($tag_term->description__value), 
                                  'link'=> \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp/'. $tag_term->tid, 
                                  'img_path'=>$img_path);
    }
    
    // dpm($category_sp_menu);

    
    // $nids = \Drupal::entityQuery('node')
    //         ->condition('type','the_shop_privilege')
    //         ->condition('status', 1)
    //         ->condition('field_type_privilege','57942')
    //         ->range(0, 4)
    //         ->execute();


    $dinner = array();
    $hotel  = array();
    $health = array();
    $education = array();
    $lifestyle = array();

    // อาหาร & เครื่องดื่ม
    $nids_dinner = \Drupal::entityQuery('node')
    ->condition('type','the_shop_privilege')
    ->condition('status', 1)
    ->condition('field_type_privilege','57942')
    ->range(0, 4)
    ->sort('changed' , 'DESC')
    ->execute();

    foreach( Node::loadMultiple($nids_dinner) as $node ) {
      $article        = $node->label();

      $in             = $node->get('body')->getValue()[0]['value'];
      $detail         = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
      $img_path       = Utils::get_file_url($node->get('field_image')->getValue()[0]['target_id']);

          $dinner[] = array(
                            'img_path'    => $img_path,
                            'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
                            'title'       => '',
                            'article'     => $article,
                            'detail'      => $detail,
                          );
    }


    // โรงแรม ที่พัก & ท่องเที่ยว
    $nids_hotel = \Drupal::entityQuery('node')
    ->condition('type','the_shop_privilege')
    ->condition('status', 1)
    ->condition('field_type_privilege','57943')
    ->range(0, 4)
    ->sort('changed' , 'DESC')
    ->execute();

    foreach( Node::loadMultiple($nids_hotel) as $node ) {
      $article        = $node->label();

      $in             = $node->get('body')->getValue()[0]['value'];
      $detail         = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
      $img_path       = Utils::get_file_url($node->get('field_image')->getValue()[0]['target_id']);

          $hotel[] = array(
                            'img_path'    => $img_path,
                            'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
                            'title'       => '',
                            'article'     => $article,
                            'detail'      => $detail,
                          );
    }

    // สุขภาพ & ความงาม
    $nids_health = \Drupal::entityQuery('node')
    ->condition('type','the_shop_privilege')
    ->condition('status', 1)
    ->condition('field_type_privilege','57944')
    ->range(0, 4)
    ->sort('changed' , 'DESC')
    ->execute();

    foreach( Node::loadMultiple($nids_health) as $node ) {
      $article        = $node->label();

      $in             = $node->get('body')->getValue()[0]['value'];
      $detail         = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
      $img_path       = Utils::get_file_url($node->get('field_image')->getValue()[0]['target_id']);

          $health[] = array(
                            'img_path'    => $img_path,
                            'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
                            'title'       => '',
                            'article'     => $article,
                            'detail'      => $detail,
                          );
    }

    // การศึกษา
    $nids_education = \Drupal::entityQuery('node')
    ->condition('type','the_shop_privilege')
    ->condition('status', 1)
    ->condition('field_type_privilege','57945')
    ->range(0, 3)
    ->sort('changed' , 'DESC')
    ->execute();

    foreach( Node::loadMultiple($nids_education) as $node ) {
      $article        = $node->label();

      $in             = $node->get('body')->getValue()[0]['value'];
      $detail         = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
      $img_path       = Utils::get_file_url($node->get('field_image')->getValue()[0]['target_id']);

          $education[] = array(
                            'img_path'    => $img_path,
                            'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
                            'title'       => '',
                            'article'     => $article,
                            'detail'      => $detail,
                          );
    }

    // ไลฟ์สไตล์
    $nids_lifestyle = \Drupal::entityQuery('node')
    ->condition('type','the_shop_privilege')
    ->condition('status', 1)
    ->condition('field_type_privilege','57946')
    ->range(0, 3)
    ->sort('changed' , 'DESC')
    ->execute();

    foreach( Node::loadMultiple($nids_lifestyle) as $node ) {
      $article        = $node->label();

      $in             = $node->get('body')->getValue()[0]['value'];
      $detail         = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
      $img_path       = Utils::get_file_url($node->get('field_image')->getValue()[0]['target_id']);

          $lifestyle[] = array(
                            'img_path'    => $img_path,
                            'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
                            'title'       => '',
                            'article'     => $article,
                            'detail'      => $detail,
                          );
    }
    
    // foreach( Node::loadMultiple($nids) as $node ) {
    //   $type_privilege = $node->get('field_type_privilege')->target_id;

    //   $article        = $node->label();

    //   $in             = $node->get('body')->getValue()[0]['value'];
    //   $detail         = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
    //   $img_path       = Utils::get_file_url($node->get('field_image')->getValue()[0]['target_id']);

    //   switch($type_privilege){
    //     // อาหาร & เครื่องดื่ม
    //     case '57942':{
    //       $dinner[] = array(
    //                         'img_path'    => $img_path,
    //                         'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
    //                         'title'       => '',
    //                         'article'     => $article,
    //                         'detail'      => $detail,
    //                       );
    //     break;
    //     }

    //     // โรงแรม ที่พัก & ท่องเที่ยว
    //     case '57943':{
    //       $hotel[] = array(
    //         'img_path'    => $img_path,
    //         'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
    //         'title'       => '',
    //         'article'     => $article,
    //         'detail'      => $detail,
    //       );
    //     break;
    //     }

    //     // สุขภาพ & ความงาม
    //     case '57944':{
    //       $health[] = array(
    //         'img_path'    => $img_path,
    //         'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
    //         'title'       => '',
    //         'article'     => $article,
    //         'detail'      => $detail,
    //       );
    //     break;
    //     }

    //     // การศึกษา
    //     case '57945':{
    //       $education[] = array(
    //         'img_path'    => $img_path,
    //         'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
    //         'title'       => '',
    //         'article'     => $article,
    //         'detail'      => $detail,
    //       );
    //     break;
    //     }

    //     // ไลฟ์สไตล์
    //     case '57946':{
    //       $lifestyle[] = array(
    //         'img_path'    => $img_path,
    //         'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
    //         'title'       => '',
    //         'article'     => $article,
    //         'detail'      => $detail,
    //       );
    //     break;
    //     }
    //   }
    // }
    
    /*
    $dinner = [
                0 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_auntie-anne_s_4.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                    //   'start_date'  => '01 กุมภาพันธ์ 2563',
                    //   'end_date'    => '31 ธันวาคม 2563',
                    //   'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                    //   'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                    //                     2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                    //                     3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                    //                     4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                    //                     5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                    //                     6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                    //                     7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    // 'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    // 'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    // 'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                1 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_184.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                2 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_181.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                3 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_goodday-shabu_2.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                
                ];
                
    
    
    $hotel = [
                0 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_art-in_10.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                1 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_teddy-bear_3.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                2 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_magic-eye.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                3 => [
                        'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_art-in_8.jpg',
                        'link'        => '',
                        'title'       => '',
                        'article'     => 'Eattention please café',
                        'detail'      => 'ร้านอาหาร คาเฟ่',
                        'start_date'  => '01 กุมภาพันธ์ 2563',
                        'end_date'    => '31 ธันวาคม 2563',
                        'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                        'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                          2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                          3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                          4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                          5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                          6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                          7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                      'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                      'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                      'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                      ],
              ];
              
             
    $health = [
                0 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_eattention_2.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                1 => [
                      'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_159.jpg',
                      'link'        => '',
                      'title'       => '',
                      'article'     => 'Eattention please café',
                      'detail'      => 'ร้านอาหาร คาเฟ่',
                      'start_date'  => '01 กุมภาพันธ์ 2563',
                      'end_date'    => '31 ธันวาคม 2563',
                      'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                      'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                        2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                        3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                        4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                        5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                        6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                        7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                    'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                    'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                    'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                    ],
                2 => [
                        'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px__10__2.jpg',
                        'link'        => '',
                        'title'       => '',
                        'article'     => 'Eattention please café',
                        'detail'      => 'ร้านอาหาร คาเฟ่',
                        'start_date'  => '01 กุมภาพันธ์ 2563',
                        'end_date'    => '31 ธันวาคม 2563',
                        'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                        'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                          2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                          3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                          4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                          5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                          6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                          7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                      'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                      'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                      'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                      ],
              ];
              

              
    $education = [
                  0 => [
                        'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_eattention_2.jpg',
                        'link'        => '',
                        'title'       => '',
                        'article'     => 'Eattention please café',
                        'detail'      => 'ร้านอาหาร คาเฟ่',
                        'start_date'  => '01 กุมภาพันธ์ 2563',
                        'end_date'    => '31 ธันวาคม 2563',
                        'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                        'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                          2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                          3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                          4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                          5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                          6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                          7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                      'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                      'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                      'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                      ],
                  1 => [
                          'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_159.jpg',
                          'link'        => '',
                          'title'       => '',
                          'article'     => 'Eattention please café',
                          'detail'      => 'ร้านอาหาร คาเฟ่',
                          'start_date'  => '01 กุมภาพันธ์ 2563',
                          'end_date'    => '31 ธันวาคม 2563',
                          'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                          'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                            2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                            3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                            4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                            5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                            6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                            7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
                        'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
                        'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
                        'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
                        ],
                ];
                

    $lifestyle = [
      0 => [
            'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_eattention_2.jpg',
            'link'        => '',
            'title'       => '',
            'article'     => 'Eattention please café',
            'detail'      => 'ร้านอาหาร คาเฟ่',
            'start_date'  => '01 กุมภาพันธ์ 2563',
            'end_date'    => '31 ธันวาคม 2563',
            'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
            'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                              2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                              3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                              4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                              5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                              6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                              7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
          'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
          'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
          'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
          ],
      1 => [
            'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_159.jpg',
            'link'        => '',
            'title'       => '',
            'article'     => 'Eattention please café',
            'detail'      => 'ร้านอาหาร คาเฟ่',
            'start_date'  => '01 กุมภาพันธ์ 2563',
            'end_date'    => '31 ธันวาคม 2563',
            'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
            'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                              2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                              3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                              4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                              5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                              6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                              7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
          'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
          'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
          'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
          ],
      2 => [
                'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px__10__2.jpg',
                'link'        => '',
                'title'       => '',
                'article'     => 'Eattention please café',
                'detail'      => 'ร้านอาหาร คาเฟ่',
                'start_date'  => '01 กุมภาพันธ์ 2563',
                'end_date'    => '31 ธันวาคม 2563',
                'privilege'   => 'คะแนนบิ๊กการ์ด 10 คะแนน แลกรับ ส่วนลด 8% สำหรับการจองโรงแรมที่ร่วมรายการ',
                'condition'   => '1. สิทธิพิเศษสำหรับสมาชิกบิ๊กการ์ดที่มีคะแนนสะสมตั้งแต่ 10 คะแนนขึ้นไป โดยทำการแลกคะแนนสะสมผ่านโทรศัพท์มือถือ และประมวลผลหักลบในยอดคงเหลือทันที
                                  2. ส่วนลดยังไม่รวม VAT 7% และ Service Charge 10%
                                  3. 1 รหัส/ สิทธิ์/สมาชิก โดยสมาชิกบิ๊กการ์ดต้องกดรับสิทธิ์ผ่านโทรศัพท์มือถือและนำรหัสที่ได้รับจองผ่านเว็บไซต์ www.agoda.com/bigc
                                  4. ไม่สามารถใช้รหัสที่เกิดจากการบันทึกหน้าจอโทรศัพท์มาแสดงเพื่อขอรับสิทธิ์
                                  5. ในกรณีที่มีการกดรหัสแล้ว ไม่ได้นำมาใช้สิทธิไม่ว่ากรณีใดก็ตาม ทางบริษัทฯ ขอสงวนสิทธิ์ในการชดเชยให้ในทุกกรณี
                                  6. เงื่อนไขและส่วนลดเป็นไปตามที่โรงแรมกำหนด ไม่สามารถแลกเปลี่ยนหรือทอนเป็นเงินสด และไม่สามารถใช้ร่วมกับส่วนลดอื่น บัตรสะสม หรือโปรโมชั่นอื่นได้
                                  7. ทางบริษัทฯ ขอสงวนสิทธิ์การเปลี่ยนแปลงเงื่อนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า โดยเงื่อนไขอื่นๆ จะเป็นไปตามที่บริษัทฯ และร้านค้ากำหนด',
              'facebook_share' => 'https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fbigcard.bigc.co.th%2Fad31122020.html&p[images][0]=https%3A%2F%2Fbigcard.bigc.co.th%2Fmedia%2Fcatalog%2Fproduct%2Fcache%2F3%2Fimage%2F9df78eab33525d08d6e5fb8d27136e95%2Fi%2Fn%2Finside-banner-420x690px_agoda_2.jpg&p[title]=Agoda&p[summary]=%3Cp%3E%3Cspan%20style%3D%22font-size%3A%20large%3B%22%3Eabc%20%E0%B8%97%E0%B8%B5%E0%B9%88%E0%B8%9E%E0%B8%B1%E0%B8%81%26nbsp%3B%3C%2Fspan%3E%3C%2Fp%3E',
              'redeem-mobile'  => '*783*45*850*บัตรประชาชน 13 หลัก# โทรออก (ฟรี)',
              'more_info'      => 'เว็บไซต์จองโรงแรม ที่พัก',
              ],
    ];

    $special_promotion =
    [
      0 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
            'title'    => 'Bigcard Exprice',
            'link'     => 'https://bigcard.bigc.co.th/register-1200',
           ],
      1 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-s-p.jpg',
            'title'    => 'ช่องทางตรวจสอบคะแนน',
            'link'     => 'https://bigcard.bigc.co.th/register-1200',
           ],
      2 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-freeticket.jpg',
            'title'    => 'ช่องทางตรวจสอบคะแนน',
            'link'     => 'https://bigcard.bigc.co.th/register-1200',
           ],
      3 => ['img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-free.jpg',
            'title'    => 'ช่องทางตรวจสอบคะแนน',
            'link'     => 'https://bigcard.bigc.co.th/register-1200',
           ],
    ];
    */

    $nids = \Drupal::entityQuery('node')
            ->condition('type','the_shop_privilege')
            ->condition('field_special_promotion', 1)
            ->condition('status', 1)
            ->sort('changed','DESC')
            ->range(0, 4)
            ->execute();

    $special_promotion = array();
    foreach( Node::loadMultiple($nids) as $node ) {
      $img_path       = Utils::get_file_url($node->get('field_image_vertical')->getValue()[0]['target_id']);
      $special_promotion[] = array(
                                    'img_path' => $img_path ,
                                    'title'    => $node->label(),
                                    // 'link'     => 'https://bigcard.bigc.co.th/register-1200',
                                    'link'     => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
                                  );
    }



    $block = [
      '#theme'  => 'front-page',
      '#cache'  => array("max-age" => 0),

      // '#role'   => MainPageController::userRole(),
      '#params' => $params,

      '#slid_main' => $slid_main, 
      '#main_r' => $main_r,
      '#category_sp_menu' => $category_sp_menu,
      '#special_promotion' => $special_promotion,
      '#dinner' => $dinner,
      '#hotel' => $hotel,
      '#health' => $health,
      '#education' => $education,
      '#lifestyle' => $lifestyle,

    ];
    // $block['#attached']['library'][] = 'credit_sales_approval/js_forms';

    return $block;
  }
}
