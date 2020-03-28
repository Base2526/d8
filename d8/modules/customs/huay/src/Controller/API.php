<?php

/**
 * @file
 * Contains \Drupal\test_api\Controller\APIController.
 */

namespace Drupal\huay\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\paragraphs\Entity\Paragraph;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller routines for test_api routes.
 */
class API extends ControllerBase {

  public function api_login(){
    $params = $_POST['params'];

    $response['result']   = TRUE;
    $response['function'] = 'api_login';
    return new JsonResponse( $response );
  }

  public function api_register(){
    $params = $_POST['params'];

    $response['result'] = TRUE;
    $response['function'] = 'api_register';
    return new JsonResponse( $response );
  }

  public function api_reset_password(){
    $params = $_POST['params'];

    $response['result'] = TRUE;
    $response['function'] = 'api_reset_password';
    return new JsonResponse( $response );
  }

  /**
   * Callback for `my-api/post.json` API method.
   */
  public function post_example( Request $request ) {

    // This condition checks the `Content-type` and makes sure to 
    // decode JSON string from the request body into array.
    // if ( 0 === strpos( $request->headers->get( 'Content-Type' ), 'application/json' ) ) {
    //   $data = json_decode( $request->getContent(), TRUE );
    //   $request->request->replace( is_array( $data ) ? $data : [] );
    // }

    $data = json_decode( $request->getContent(), TRUE );

    // pull the raw binary data from the POST array
    // $fdata = $_POST['fdata'];
    // decode it
    // $decodedData = base64_decode($data);
    // // print out the raw data,
    // $filename = $_POST['fname'];
    // // echo $filename;
    // // write the data out to the file
    // $fp = fopen('sites/default/files/'.$filename, 'wb');
    // fwrite($fp, $decodedData);
    // fclose($fp);

    // $img = str_replace('data:image/png;base64,', '', $img);
    // $img = str_replace(' ', '+', $img);

    // $data = base64_decode($img);
    // $file = uniqid() . '.png';

    // $success = file_put_contents($file, $data);

    // pull the raw binary data from the POST array
    // $data = substr($_POST['fdata'], strpos($_POST['fdata'], ",") + 1);
    // // decode it
    // $decodedData = base64_decode($data);
    // // print out the raw data, 
    // echo ($decodedData);
    // $filename = "1000.png";
    // // write the data out to the file
    // $fp = fopen('sites/default/files/'. $filename, 'wb');
    // fwrite($fp, $decodedData);
    // fclose($fp);

    // file_put_contents('sites/default/files/'. $filename, $_POST['fdata']);

    // $info = pathinfo($_FILES['fdata']['name']);
    // $ext = $info['extension']; // get the extension of the file
    // $newname = "newnamemmm.".$ext; 

    $params = $_POST['params'];
    $fileName = $_POST['fname'];

    $arr_params = explode("-",$params);

    $tid = $arr_params[1];
    $key = $arr_params[2];
    $target_id  = $arr_params[3];

    

    $target = 'sites/default/files/'. $fileName;
    move_uploaded_file( $_FILES['fdata']['tmp_name'], $target);

    $file_temp = file_get_contents( $target);
    $file = file_save_data($file_temp, 'public://'. date('m-d-Y_hia') .'.png' , FILE_EXISTS_RENAME);


    $p = Paragraph::load($target_id);

    $p->set('field_image', array('target_id'=>$file->id()));
    $p->save();
    /*
    http://www.drupal8.ovh/en/tutoriels/47/create-a-file-drupal-8
    https://stackoverflow.com/questions/7407840/uploading-and-saving-a-file-programmatically-to-drupal-nodes
    $f= array(
      'fid' => $file->fid,
      'filename' => $file->filename,
      'filemime' => $file->filemime,
      'uid' => 1,
      'uri' => $file->uri,
      'status' => 1
    );
    */

    // $response['data'] = 'Some test data to return uri :' .  drupal_realpath($file->getFileUri()) . ', fid : ' . $file->id() . ', getFilename : '. $file->getFilename() ;
    $response['result'] = TRUE;
    $response['data'] = array('id'=>$file->id(), 'fid'=>$file->id(), 'p'=>$p);
    $response['arr_params'] = $arr_params;
    // $response['target_id'] =  array('target_id'=>$file->fid());
    return new JsonResponse( $response );
  }

}