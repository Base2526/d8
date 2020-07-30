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

class SpecialPrivilegesForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'special_privileges_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $route_match = \Drupal::service('current_route_match');
        $tid = $route_match->getParameter('tid');
        // dpm($tid);

        //header
        $head_dinner_img = 'https://bigcard.bigc.co.th/media/catalog/category/cate-dining.png';
        $head_dinner_title = '';

        $special_privileges_background = ConfigPages::config('special_privileges_background');
        switch($tid){
            // อาหาร & เครื่องดื่ม
            case '57942':{
                $head_dinner_img   = Utils::get_file_url($special_privileges_background->get('field_dining_bg')->getValue()[0]['target_id']); 
                
                $head_dinner_title = 'อาหาร &amp; เครื่องดื่ม';
            break;
            }
    
            // โรงแรม ที่พัก & ท่องเที่ยว
            case '57943':{
                $head_dinner_img   = Utils::get_file_url($special_privileges_background->get('field_hotel_travel_bg')->getValue()[0]['target_id']); 
                
                $head_dinner_title = 'โรงแรม ที่พัก &amp; ท่องเที่ยว';
            break;
            }
    
            // สุขภาพ & ความงาม
            case '57944':{
                $head_dinner_img   = Utils::get_file_url($special_privileges_background->get('field_health_beauty_bg')->getValue()[0]['target_id']); 
                
                $head_dinner_title = 'สุขภาพ &amp; ความงาม';
            break;
            }
    
            // การศึกษา
            case '57945':{
                $head_dinner_img   = Utils::get_file_url($special_privileges_background->get('field_edutainment_bg')->getValue()[0]['target_id']); 
            
                $head_dinner_title = 'การศึกษา';
            break;
            }
    
            // ไลฟ์สไตล์
            case '57946':{
                $head_dinner_img   = Utils::get_file_url($special_privileges_background->get('field_lifestyle_bg')->getValue()[0]['target_id']);
           
                $head_dinner_title = 'ไลฟ์สไตล์';
            break;
            }
        }
        

        // //header
        // $head_dinner_img = 'https://bigcard.bigc.co.th/media/catalog/category/cate-dining.png';
        // $head_dinner_title = 'อาหาร &amp; เครื่องดื่ม';
        $bg_card_img = 'https://bigcard.bigc.co.th/skin/frontend/default/bigcard-new2018/images/icon-special-points2x.png';
        $bg_card_title = "ใช้คะแนนแลกรับสิทธิพิเศษจากร้านค้าต่างๆ";

        //highlight
        // $array_hightlight = [
        //                         0 => [
        //                         'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
        //                         'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
        //                         ],
        //                         1 => [
        //                         'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
        //                         'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
        //                         ],
        //                         2 => [
        //                         'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
        //                         'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
        //                         ],
        //                         3 => [
        //                         'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
        //                         'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
        //                         ],
        //                     ];

        $array_hightlight = array();

        $hightlight_nids = \Drupal::entityQuery('node')
                            ->condition('type','the_shop_privilege')
                            ->condition('field_type_privilege', $tid)
                            ->condition('status', 1)
                            ->condition('field_highlight', 1)
                            ->sort('changed','DESC')
                            ->range(0, 4)
                            ->execute();
        $hightlight_nodes = Node::loadMultiple($hightlight_nids);
        foreach( $hightlight_nodes as $hightlight_node ) {
            $img_path = '';                
            $im = $hightlight_node->get('field_image_vertical');
            if(!empty($im->getValue())){ 
              $img_path = Utils::get_file_url($im->getValue()[0]['target_id']); 
              dpm($img_path);
            }
            $array_hightlight[] = array('img_path' => $img_path,  'title'=> 'ใช้ 2000 คะแนน แลกรับ ฟรี',  'link' => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $hightlight_node->id(),);
        }

        $hightlight_desktop = '';
        $hightlight_mobile = '';
        foreach($array_hightlight as $array_hightlight_v)
        {
            $hightlight_desktop = $hightlight_desktop .
            '<div class="col-lg-3 col-md-3 col-sm-12 col-12">
                <a href="'.$array_hightlight_v['link'].'" title="Shong Cha"> 
                <img title="'.$array_hightlight_v['title'].'" src="'.$array_hightlight_v['img_path'].'" alt="'.$array_hightlight_v['title'].'">
                </a>
            </div>';

            $hightlight_mobile = $hightlight_mobile .
            '<div class="carousel-item active w-100">
                <a href="'.$array_hightlight_v['link'].'" title="Shong Cha"> 
                <img src="'.$array_hightlight_v['img_path'].'" alt="'.$array_hightlight_v['title'].'" style="width: 100%;>
                </a>
            </div>';
        }

        // all_deals_data
        /*
        $array_all_data = [
                    0 => [
                        'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px_auntie-anne_s_4.jpg',
                        'link'        => '',
                        'title'       => '',
                        'article'     => 'Eattention please café',
                        'detail'      => 'ร้านอาหาร คาเฟ่',
                        ],
                    1 => [
                        'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_184.jpg',
                        'link'        => '',
                        'title'       => '',
                        'article'     => 'Eattention please café',
                        'detail'      => 'ร้านอาหาร คาเฟ่',
                        ],
                    2 => [
                        'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_184.jpg',
                        'link'        => '',
                        'title'       => '',
                        'article'     => 'Eattention please café',
                        'detail'      => 'ร้านอาหาร คาเฟ่',
                        ],
                    3 => [
                        'img_path'    => 'https://bigcard.bigc.co.th/media/catalog/product/cache/3/thumbnail/9df78eab33525d08d6e5fb8d27136e95/i/n/inside-banner-420x690px___3_184.jpg',
                        'link'        => '',
                        'title'       => '',
                        'article'     => 'Eattention please café',
                        'detail'      => 'ร้านอาหาร คาเฟ่',
                        ],
                 ];
                 */

        $current  = 1;
        $p_start  = 1;
        $p_length = 18;

        if( !empty($_GET['p']) ){
            $current  = $_GET['p'];
            switch($_GET['p']){
                case 2:{
                    $p_start  = 19;
                    $p_length = 37;
                break;
                }

                case 3:{
                    $p_start  = 38;
                    $p_length = 56;
                break;
                }

                case 4:{
                    $p_start  = 57;
                    $p_length = 75;
                break;
                }

                case 5:{
                    $p_start  = 76;
                    $p_length = 94;
                break;
                }

                case 6:{
                    $p_start  = 95;
                    $p_length = 113;
                break;
                }

                case 7:{
                    $p_start  = 114;
                    $p_length = 132;
                break;
                }
            }
        }

        $nids = \Drupal::entityQuery('node')
                 ->condition('type','the_shop_privilege')
                 // ->condition('uid',  \Drupal::currentUser()->id())
                 ->condition('field_type_privilege', $tid)
                 ->condition('status', 1)
                 ->range($p_start, $p_length)
                 ->execute();
 
        $nodes = Node::loadMultiple($nids);

        // dpm($nodes);

        $array_all_data = array();
        foreach( $nodes as $node ) {
            // $link = $node->get('field_item_slide_link')->getValue()[0]['value'];

            $img_path = '';                
            $im = $node->get('field_image');
            if(!empty($im->getValue())){ 
              $img_path = Utils::get_file_url($im->getValue()[0]['target_id']); 
            }

            $in             = $node->get('body')->getValue()[0]['value'];
            $detail         = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
            $array_all_data[] = array(  'img_path'=>$img_path,
                                        'link'        => '/'. \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp_detail/' . $node->id(),
                                        'title'       => '',
                                        'article'     => $node->label(),
                                        'detail'      => $detail,
                                        );
        }
        
        $all_data = '';
        foreach($array_all_data as $array_all_data_v)
        {
            $all_data = $all_data.
            '<div class="col-lg-4 col-md-4 col-6 col-sm-6">
                <div class="item">
                    <div class="cover-img cover-view">
                    <a href="'.$array_all_data_v['link'].'" title="Shong Cha"><img src="'.$array_all_data_v['img_path'].'" alt="'.$array_all_data_v['title'].'"></a>
                    </div>
                    <div class="info pt-2">
                    <a href="'.$array_all_data_v['link'].'">
                        <span class="font_tile">'.$array_all_data_v['article'].'</span>
                    </a>
                        <p class="desc"></p><p align="left"><span>'.$array_all_data_v['detail'].'</span></p><p></p>
                    </div> 
                </div>
            </div>';
        }

        $form['header'] = array(
            '#type'     => 'item',
            '#markup'   => '',
            '#prefix'   => '
                <div class="category-box-info">
                    <div class="row margin_lr0">
                        <div class="main-wrapper gray">
                            <p class="category-image"><img src="'.$head_dinner_img.'" alt='.$head_dinner_title.'" title="'.$head_dinner_title.'"></p>                
                            <div class="info-text"> 
                                <div class="icon-cate-info show_th"><img src="'.$bg_card_img.'" alt="'.$bg_card_title.'"></div>
                                <h1 class="pr-2">'.$head_dinner_title.'
                                    <div class="line-bottom">&nbsp;</div>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>',
            '#suffix'   => ''
            );        

          $form['highlight_pre'] = array(
            '#type'     => 'item',
            '#markup'   => '',
            '#prefix'   => '
                <div class="main-wrapper">
                    <div class="section_cate">
                        <div class="block-title-dinner">
                            <div class="main-title paddingTop">
                                <h2>HIGHLIGHT</h2>
                                <div class="line-bottom">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>',
            '#suffix'   => ''
          );


          $form['highlight_desktop'] = array(
            '#type'     => 'item',
            '#markup'   => '',
            '#prefix'   => ' 
            <div class="main-wrapper">
                <div class="highlight-show desktop-show">
                    <div class="wrap-bannerads">
                        <div class="div-banner">
            
                            <div class="row m15">
                              '.$hightlight_desktop.'
                            </div>
 
            
                        </div>
                    </div>
                </div>
            </div>',
            '#suffix'   => ''
          );

          $form['highlight_mobile'] = array(
            '#type'     => 'item',
            '#markup'   => '',
            '#prefix'   => '  
            <div class="highlight-show mobile-show">
                <div class="wrap-bannerads">
                    <div class="div-banner">

                        <div class="row m15">
                            <div class="col-sm-12 col-12">
                                
                                <div class="banner-carousel">
                                    <div id="myCarousel1" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        <li data-target="#myCarousel1" data-slide-to="0" class="active"></li>
                                        <li data-target="#myCarousel1" data-slide-to="1"></li>
                                        <li data-target="#myCarousel1" data-slide-to="2"></li>
                                    </ol>
                                
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner">
                                     '.$hightlight_mobile.'
                                    </div>
                                
                                    <!-- Left and right controls -->
                                    <a class="carousel-control-prev" role="button" href="#myCarousel1" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" role="button" href="#myCarousel1" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                    </div>
                                </div>
                            
                            </div>
                        </div>

                    </div>
                </div>
            </div>',
            '#suffix'   => '<p>&nbsp;</p>'
          );

          $form['all_deals_head'] = array(
            '#type'     => 'item',
            '#markup'   => '',
            '#prefix'   => '
            <div class="cate_area">
                <div class="graybg">
                    <div class="main-wrapper">
                        <div class="section_cate">
                            <div class="block-title-dinner">
                                <div class="main-title">
                                    <h2>all deals</h2>
                                    <div class="line-bottom">&nbsp;</div>
                                </div>
                            </div>',
            '#suffix'   => ''
          );

        $form['all_deals_data'] = array(
            '#type'     => 'item',
            '#markup'   => '',
            '#prefix'   => '
            <div class="category-products">
                <div class="row">
                    '.$all_data.'
                </div>
            </div>',
            '#suffix'   => ''
        );

        $form['all_deals_data_post'] = array(
        '#type'     => 'item',
        '#markup'   => '',
        '#prefix'   => '</div>
                        </div>
                        </div>',
        '#suffix'   => '<p>&nbsp;</p>'
        );

        
        $count = \Drupal::entityQuery('node')
            ->condition('type', "the_shop_privilege")
            ->condition('field_type_privilege', $tid)
            ->condition('status', 1)
            ->count()->execute();
        // dpm($count);
        $pagging = intval($count / 18);
        if($count % 18 > 0){
            $pagging++;
        }

        if($pagging > 1){

            // dpm();

            $li_pagging = '';
            for ($x = 0; $x < $pagging; $x++) {
                // $pagging .='<li class="page-prev">
                //                 <a class="prev" href="'. Utils::get_base_url() . \Drupal::languageManager()->getCurrentLanguage()->getId() .'/dining.html?p=2" title="ถัดไป"><p class="prev-pager"></p></a>
                //             </li>
                //             <li class="current">1</li>
                //             <li>
                //                 <a href="https://bigcard.bigc.co.th/dining.html?p=2">2</a>
                //             </li>
                //             <li class="page-next">
                //                 <a class="next" href="https://bigcard.bigc.co.th/dining.html?p=2" title="ถัดไป"><p class="next-pager"></p></a>
                //             </li>';

                if( $x == $current - 1 ){
                    $li_pagging .= '<li class="current">'. ($x + 1) .'</li>';
                }else{
                    $li_pagging .= '<li><a href="'. Utils::get_base_url() . \Drupal::languageManager()->getCurrentLanguage()->getId() .'/sp/' . $tid . '?p=' . ($x + 1) . '">' . ($x + 1) . '</a></li>';
                }
            }

            $form['page'] = array(
                '#type'     => 'item',
                '#markup'   => '',
                '#prefix'   => '    
                <div class="toolbar-bottom">
                    <div class="perpage">
                        <ul>'. $li_pagging.'</ul>
                    </div>
                </div>',
                '#suffix'   => ''
            );

            /*
            <li class="page-prev">
                <a class="prev" href="https://bigcard.bigc.co.th/dining.html?p=2" title="ถัดไป"><p class="prev-pager"></p></a>
            </li>
            <li class="current">1</li>
            <li>
                <a href="https://bigcard.bigc.co.th/dining.html?p=2">2</a>
            </li>
            <li class="page-next">
                <a class="next" href="https://bigcard.bigc.co.th/dining.html?p=2" title="ถัดไป"><p class="next-pager"></p></a>
            </li>
            */
        }
        
        $form['close_div'] = array(
            '#type'     => 'item',
            '#markup'   => '</div><p>&nbsp;</p>',
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
