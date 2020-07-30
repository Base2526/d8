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

use Picqer\Barcode\BarcodeGeneratorPNG;
use Endroid\QrCode\QrCode;

use Drupal\bigcard\Utils\Utils;

class LifestyleForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'lifestyle_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        // $form['left']['img_card'] = array(
        // '#type'     =>'markup',
        // '#markup'   =>'** lifestyle_form',            
        // );

        $test_code = '081231723897';

        // https://github.com/picqer/php-barcode-generator
        $redColor = [255, 0, 0];
        $generator = new BarcodeGeneratorPNG();
        $form['img_barcode'] = array(
            '#type'     =>'markup',
            '#markup'   =>'<img src="' . Utils::base64ToImage("data:image/png;base64," .base64_encode($generator->getBarcode($test_code, $generator::TYPE_CODE_128)), base64_encode(\Drupal::currentUser()->id()) . '_barcode.png')  . '" alt="barcode">',          
        );

        // https://github.com/endroid/qr-code
        $qrCode = new QrCode($test_code);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setLogoSize(150, 200);
        $qrCode->setValidateResult(false);

        // Save it to a file
        $qrCode->writeFile(__DIR__.'/qrcode.png');

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $qrCode->writeDataUri();
        // dpm($dataUri);
        $form['img_qrcode'] = array(
            '#type'     =>'markup',
            '#markup'   =>'<img src="' . Utils::base64ToImage($dataUri, base64_encode(\Drupal::currentUser()->id()) . '_qrcode.png')  . '" alt="qrcode">',          
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
