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

class DiningForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'dining_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {



        //header
        $head_dinner_img = 'https://bigcard.bigc.co.th/media/catalog/category/cate-dining.png';
        $head_dinner_title = 'อาหาร &amp; เครื่องดื่ม';
        $bg_card_img = 'https://bigcard.bigc.co.th/skin/frontend/default/bigcard-new2018/images/icon-special-points2x.png';
        $bg_card_title = "ใช้คะแนนแลกรับสิทธิพิเศษจากร้านค้าต่างๆ";

        //highlight
        $array_hightlight = [
                                0 => [
                                'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
                                'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
                                ],
                                1 => [
                                'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
                                'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
                                ],
                                2 => [
                                'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
                                'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
                                ],
                                3 => [
                                'img_path' => 'https://bigcard.bigc.co.th/media/bannerads/images/highlight-block-290x410px-dq-itim.jpg',
                                'title'    => 'ใช้ 2000 คะแนน แลกรับ ฟรี',
                                ],
                            ];
        $hightlight_desktop = '';
        $hightlight_mobile = '';
        foreach($array_hightlight as $array_hightlight_v)
        {
            $hightlight_desktop = $hightlight_desktop .
            '<div class="col-lg-3 col-md-3 col-sm-12 col-12">
                <img title="ใช้ 2000 คะแนน แลกรับ ฟรี" src="'.$array_hightlight_v['img_path'].'" alt="'.$array_hightlight_v['title'].'">
            </div>';

            $hightlight_mobile = $hightlight_mobile .
            '<div class="carousel-item active w-100">
            <img src="'.$array_hightlight_v['img_path'].'" alt="'.$array_hightlight_v['title'].'" style="width: 100%;>
            </div>';
        }

        // all_deals_data
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

        $form['page'] = array(
            '#type'     => 'item',
            '#markup'   => '',
            '#prefix'   => '    
            <div class="toolbar-bottom">
                <div class="perpage">
                    <ul>
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
                    </ul>
                </div>
            </div>',
            '#suffix'   => '<p>&nbsp;</p></div>'
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
