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

class ImportExcelForm extends FormBase {
    public $attachFileDescription = '<div class="attachmentDescription">Allowed extensions: xlsx, xls <br>Maximum upload file size: 10 MB</div>';
    public $allowFileExtension    = array('xlsx xls');
    public $allowFileSize         = array(10 * 1024 * 1024);

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'import_excel_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['#tree'] = TRUE;
        $form['type'] = array(
            '#type' => 'select',
            '#title' => $this->t('เลือกประเภทข้อมูลนําเข้า'),
            '#empty_option' => '- เลือก -',
            '#options'      => ['จังหวัด', 'อำเภอ', 'ตำบล', 'รหัสไปรษณีย์', ],
        );

        $form['attach_file'] = [
            '#type' => 'managed_file',
            '#upload_location' => 'public://',
            '#description' => t($this->attachFileDescription),
            '#upload_validators' => array(
                'file_validate_extensions' => $this->allowFileExtension,
                // Pass the maximum file size in bytes
                'file_validate_size' => $this->allowFileSize,
                // 'file_validate_name' => array()
            ),
        ];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('นำเข้า'),
        );

        return $form;
    }

    /**
     * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);

        $type=  $form_state->getUserInput()['type'];
        $attach_file= $form_state->getUserInput()['attach_file'];
       
        if($type < 0){
            $form_state->setErrorByName("type", $this->t('กรุณา เลือกประเภทข้อมูลนําเข้า'));
        }

        if(empty($attach_file)){
            $form_state->setErrorByName("attach_file", $this->t('กรุณา เลือกไฟล์'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        require_once 'sites/default/libraries/PHPExcel/Classes/PHPExcel.php';

        $type=  $form_state->getUserInput()['type'];
        $attach_file= $form_state->getUserInput()['attach_file'];

        /* Load the object of the file by it's fid */
        $tmpfname = File::load( $attach_file['fids'] );
        $path = file_create_url($tmpfname->getFileUri());
        $inputFileName = '.'. rawurldecode(file_url_transform_relative($path));

        $excelReader = \PHPExcel_IOFactory::createReaderForFile($inputFileName);
        $excelObj = $excelReader->load($inputFileName);
        $worksheet = $excelObj->getSheet(0);

        $lastRow = $worksheet->getHighestRow(); 

        switch($type){
            // 'จังหวัด'
            case 0:{

                $provinces_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('provinces');
                foreach($provinces_terms as $tag_term) {
                    if ($term = \Drupal\taxonomy\Entity\Term::load($tag_term->tid)) {
                        // Delete the term itself
                        $term->delete();
                    }
                }

                // provinces
                for ($row = 1; $row <= $lastRow; $row++)
                {
                    $provinces_3_code   = $worksheet->getCell('A'.$row)->getValue();
                    $provinces_title = $worksheet->getCell('B'.$row)->getValue();
                    $provinces_2_code   = $worksheet->getCell('C'.$row)->getValue();
                    $ref_id = $worksheet->getCell('D'.$row)->getValue();
                    $provinces_title_en = $worksheet->getCell('E'.$row)->getValue();

                    // dpm( $provinces_3_code );
                    $term = \Drupal\taxonomy\Entity\Term::create([
                        'vid' => 'provinces',
                        'name' => $provinces_title,
                        // 'parent' => ['target_id'=> 162 ],
                        'field_ref_id'  => $ref_id,
                        'field_provinces_2_code' => $provinces_2_code,
                        'field_provinces_3_code' => $provinces_3_code,
                        'field_title_en' => $provinces_title_en,
                        'weight'=> $ref_id
                        ]);
                    $term->save();
                }

                drupal_set_message("Update provinces succesfully.");
            break;
            }

            // 'อำเภอ'
            case 1:{
                // district
                $district_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('district');
                foreach($district_terms as $tag_term) {
                    if ($term = \Drupal\taxonomy\Entity\Term::load($tag_term->tid)) {
                        // Delete the term itself
                        $term->delete();
                    }
                }

                // district
                for ($row = 1; $row <= $lastRow; $row++)
                {
                    $ref_id = $worksheet->getCell('A'.$row)->getValue();
                    $district_title = $worksheet->getCell('B'.$row)->getValue();
                    $district_3_code   = $worksheet->getCell('C'.$row)->getValue();
                    $provinces_title_en = $worksheet->getCell('D'.$row)->getValue();

                    // dpm( $provinces_3_code );
                    $term = \Drupal\taxonomy\Entity\Term::create([
                        'vid' => 'district',
                        'name' => $district_title,
                        // 'parent' => ['target_id'=> 162 ],
                        'field_ref_id'  => $ref_id,
                        'field_provinces_3_code' => $district_3_code,
                        'field_title_en' => $provinces_title_en,
                        'weight'=> $row
                        ]);
                    $term->save();
                }

                drupal_set_message("Update district succesfully.");
            break;
            }

            // 'ตำบล'
            case 2:{
                // subdistrict
                // $subdistrict_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('subdistrict');
                // foreach($subdistrict_terms as $tag_term) {
                //     if ($term = \Drupal\taxonomy\Entity\Term::load($tag_term->tid)) {
                //         // Delete the term itself
                //         $term->delete();
                //     }
                // }

                // subdistrict
                for ($row = 1; $row <= $lastRow; $row++)
                {
                    $ref_id             = $worksheet->getCell('A'.$row)->getValue();
                    $subdistrict_title  = $worksheet->getCell('B'.$row)->getValue();
                    $district_4_code    = $worksheet->getCell('C'.$row)->getValue();
                    $district_2_3_code  = $worksheet->getCell('D'.$row)->getValue();
                    $provinces_title_en = $worksheet->getCell('E'.$row)->getValue();

                    $term = \Drupal\taxonomy\Entity\Term::create([
                        'vid' => 'subdistrict',
                        'name' => $subdistrict_title,
                        // 'parent' => ['target_id'=> 162 ],
                        'field_ref_id'  => $ref_id,
                        'field_provinces_3_code' => $district_4_code,
                        'field_provinces_2_code' => $district_2_3_code,
                        'field_title_en' => $provinces_title_en,
                        'weight'=> $row
                        ]);
                    $term->save();
                }

                drupal_set_message("Update subdistrict succesfully.");
            break;
            }

            // 'รหัสไปรษณีย์'
            case 3:{
                // postal_code
                // $postal_code_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('postal_code');
                // foreach($postal_code_terms as $tag_term) {
                //     if ($term = \Drupal\taxonomy\Entity\Term::load($tag_term->tid)) {
                //         // Delete the term itself
                //         $term->delete();
                //     }
                // }
 
                 // postal_code
                 for ($row = 1; $row <= $lastRow; $row++)
                 {
                     $postal_code_5_code    = $worksheet->getCell('A'.$row)->getValue();
                     $ref_id                = $worksheet->getCell('B'.$row)->getValue();
                     $postal_code_3_code    = $worksheet->getCell('G'.$row)->getValue();
                     $postal_code_2_code    = $worksheet->getCell('I'.$row)->getValue();
                   
                     $term = \Drupal\taxonomy\Entity\Term::create([
                         'vid' => 'postal_code',
                         'name' => $postal_code_5_code,
                         // 'parent' => ['target_id'=> 162 ],
                         'field_ref_id'  => $ref_id,
                         'field_provinces_3_code' => $postal_code_3_code,
                         'field_provinces_2_code' => $postal_code_2_code,
                         'weight'=> $row
                         ]);
                     $term->save();
                 }
 
                 drupal_set_message("Update postal code succesfully.");
 
            break;
            }
        }
    }
}
