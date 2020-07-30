<?php

namespace Drupal\bigcard\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Url;

use Drupal\node\Entity\Node;

/*
https://glassdimly.com/blog/tech/drupal-8-theming-views-planet-drupal/drupal-83-create-programmatic-custom-breadrumb
https://drupal.stackexchange.com/questions/151133/how-do-you-implement-a-breadcrumb

with parameter
Link::fromTextAndUrl(t('แก้ไข Approval Code'), Url::fromRoute('printing.form', array('nid' => $nid)));
*/
class BreadcrumbBuilder implements BreadcrumbBuilderInterface {
    /**
     * @inheritdoc
     */
    public function applies(RouteMatchInterface $route_match) {
        /* Allways use this. Change this is another module needs to use a new custom breadcrumb */
        switch($route_match->getRouteName()){
            case 'new_member.step1':
            case 'new_member.step2':
            case 'new_member.step3':
            case 'new_member.step4':
            case 'what_is_bigcard.form': 
            case 'exclusive.form':
            case 'terms_and_conditions.form':
            case 'dining.form':
            case 'hotel_travel.form':
            case 'health_beauty.form':
            case 'edutainment.form':
            case 'lifestyle.form':
            case 'step_check_point.form':
            case 'how_to_register_bigcard.form':
            case 'contact_us.form': 
            case 'question_and_answer.form':
            case 'collect_points.form':
            case 'discount_point.form':
            case 'pointfree.form': 
            case 'search.form':
            case 'sp.form':
            case 'promotion_detail.form':
            case 'special_sticker.form':
            case 'step_to_join.form':
            case 'sp_detail.form': {
                return true;
            }
            default:{
                return false;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function build(RouteMatchInterface $route_match) {
        $breadcrumb = new Breadcrumb();
        $breadcrumb->addCacheContexts(['url.path']);
    
        // Home Member New Member
        switch($route_match->getRouteName()){
            case 'new_member.step1':
            case 'new_member.step2':
            case 'new_member.step3':
            case 'new_member.step4': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('Member'), '<none>'),
                                        Link::createFromRoute(t('New Member'), '<none>'),]);
                break;
            }
            case 'what_is_bigcard.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('บิ๊กการ์ดคืออะไร'), '<none>'),]);
                break;
            }

            case 'exclusive.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('สิทธิพิเศษบิ๊กการ์ด'), '<none>'),]);
                break;
            }

            case 'terms_and_conditions.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('Terms And Conditions'), '<none>'),]);
                break;
            }

            case 'dining.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('อาหาร & เครื่องดื่ม'), '<none>'),]);
                break;
            }

            case 'hotel_travel.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('โรงแรม ที่พัก & ท่องเที่ยว'), '<none>'),]);
                break;
            }

            case 'health_beauty.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('สุขภาพ & ความงาม'), '<none>'),]);
                break;
            } 

            case 'edutainment.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('การศึกษา'), '<none>'),]);
                break;
            } 

            case 'lifestyle.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('ไลฟ์สไตล์'), '<none>'),]);
                break;
            } 

            case 'step_check_point.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('ตรวจสอบคะแนนได้ที่'), '<none>'),]);
                break;
            }

            case 'how_to_register_bigcard.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('การเข้าสู่ระบบครั้งแรก'), '<none>'),]);
                break;
            }

            case 'contact_us.form': {
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('ติตด่อเรา'), '<none>'),]);
                break;
            }

            case 'question_and_answer.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                Link::createFromRoute(t('คำถามที่พบบ่อย'), '<none>'),]);
                break;
            }

            case 'collect_points.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('สะสมคะแนน'), '<none>'),]);
                break;
            }

            case 'discount_point.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('แลกคะแนนรับส่วนลด'), '<none>'),]);
                break;
            }

            case 'pointfree.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('แลกคะแนนรับสินค้าฟรี'), '<none>'),]);
                break;
            }

            case 'search.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('ค้นหา'), '<none>'),]);
                break;
            }

            case 'promotion_detail.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('รายละเอียดโปรโมชั่น'), '<none>'),]);
                break;
            }

            case 'special_sticker.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('บิ๊กสติ๊กเกอร์พิเศษ'), '<none>'),]);
                break;
            }

            case 'step_to_join.form':{
                $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                        Link::createFromRoute(t('ขั้นตอนในการเข้าร่วม'), '<none>'),]);
                break;
            }

            case 'sp.form':{
                $route_match = \Drupal::service('current_route_match');
                $tid = $route_match->getParameter('tid');

                switch( $tid ){
                    // อาหาร & เครื่องดื่ม
                    case '57942':{
                        $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                                Link::createFromRoute(t('อาหาร & เครื่องดื่ม'), '<none>'),]);
                    break;
                    }

                    // โรงแรม ที่พัก & ท่องเที่ยว
                    case '57943':{
                        $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                                Link::createFromRoute(t('โรงแรม ที่พัก & ท่องเที่ยว'), '<none>'),]);
                    break;
                    }

                    // สุขภาพ & ความงาม
                    case '57944':{
                        $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                                Link::createFromRoute(t('สุขภาพ & ความงาม'), '<none>'),]);
                    break;
                    }

                    // การศึกษา
                    case '57945':{
                        $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                                Link::createFromRoute(t('การศึกษา'), '<none>'),]);
                    break;
                    }

                    // ไลฟ์สไตล์
                    case '57946':{
                        $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                        Link::createFromRoute(t('ไลฟ์สไตล์'), '<none>'),]);
                    break;
                    }


                }

            break;
            }

            case 'sp_detail.form':{
                $route_match = \Drupal::service('current_route_match');
                $nid = $route_match->getParameter('nid');

                $node = Node::load($nid);
                if(!empty($node)){
                    $type_privilege = $node->get('field_type_privilege')->target_id;

                    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($type_privilege);
                    $breadcrumb->setLinks([ Link::createFromRoute(t('Home'), '<front>'),
                                            Link::fromTextAndUrl(t($term->label()), Url::fromRoute('sp.form', array('tid' => $type_privilege ))),
                                            Link::createFromRoute(t($node->label()), '<none>'),
                                            ]);
                }
            break;
            }
        }
   
        return $breadcrumb;
    }
}