<?php

namespace Drupal\form_api_example\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements the ajax demo form controller.
 *
 * This example demonstrates using ajax callbacks to add people's names to a
 * list of picnic attendees.
 *
 * @see \Drupal\Core\Form\FormBase
 * @see \Drupal\Core\Form\ConfigFormBase
 */
class AjaxAddMore extends DemoBase {

  /**
   * Form with 'add more' and 'remove' buttons.
   *
   * This example shows a button to "add more" - add another textfield, and
   * the corresponding "remove" button.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This example shows an add-more and a remove-last button.'),
    ];

    // Gather the number of names in the form already.
    $num_names = $form_state->get('num_names');
    // We have to ensure that there is at least one name field.
    if ($num_names === NULL) {
      $name_field = $form_state->set('num_names', 1);
      $num_names = 1;
    }


    $test = $form_state->get('test');
    if($test === NULL){
      // $form_state->set('test', array('0'=>'A', '1'=>'B', '2'=>'C', '3'=>'D', '4'=>'E'));

      $m = array('0'=>array(
                            array('name'=>'A'),
                            array('name'=>'B')
                            ),
                 '1'=>array(
                            array('name'=>'C'),
                            array('name'=>'D')
                            ),
                 '2'=>array(
                            array('name'=>'E'),
                            array('name'=>'F')
                            ),
                 '3'=>array(
                            array('name'=>'G'),
                            array('name'=>'H')
                            ),
                 '4'=>array(
                            array('name'=>'I'),
                            array('name'=>'J')
                            ),
                 '5'=>array(
                            array('name'=>'K'),
                            array('name'=>'L'),
                            array('name'=>'M'),
                            array('name'=>'N'),
                            )
                 );
                 
      $form_state->set('test',$m);
      $test = $form_state->get('test');
    }
    

    // dpm('A');

    $form['#tree'] = TRUE;
    $form['names_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('People coming to picnic 888'),
      '#prefix' => '<div id="names-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    // for ($i = 0; $i < $num_names; $i++) {
    foreach($test as $i => $v){

      // ++$i;
      $form['names_fieldset'][$i] = [
        '#type' => 'fieldset',
        '#title' => $this->t('People coming to picnic : '. count($v) .' : '.$i ),
        '#prefix' => '<div id="fieldset-wrapper-'.$i.'">',
        '#suffix' => '</div>',
      ];
      foreach($v as $key => $values){
        $form['names_fieldset'][$i][$key]= array(
          '#type' => 'fieldset',
          '#title' => $i . '-' . $key,
          '#open' => TRUE,
          // '#prefix' => '<div id="names-fieldset-wrapper-'.$i.'">',
          //   '#suffix' => '</div>',
        );

        $form['names_fieldset'][$i][$key]['name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Name'),
          '#value' => $values['name'],
          // '#default_value' => $v,
          // '#prefix' => "<div id='update-name-value-".$i."'>",
          // '#suffix' => "</div>",
        ];

        // $form['names_fieldset'][$i][$key]['actions'] = [
        //   '#type' => 'submit',
        //   '#name' => 'remove-'.$i.'-'.$key,
        //   '#value' => $this->t('Remove + '),
        //   '#submit' => ['::removeCallback'],
        //   '#ajax' => [
        //     'callback' => '::removeXCallback',
        //     'wrapper' => 'names-fieldset-wrapper-'.$i,
        //   ],
        // ];

        $form['names_fieldset'][$i][$key]['actions'] = [
          '#type' => 'submit',
          '#name' => 'remove-'.$i.'-'.$key,
          '#value' => $this->t('Remove + '),
          '#submit' => ['::add1SubmitCallback'],
          '#ajax' => [
            'callback' => '::add1Callback',
            'wrapper' => 'fieldset-wrapper-'.$i,
            'effect' => 'slide',
          ],
        ];
        
      }

      $form['names_fieldset'][$i]['actions_add'] = [
        '#type' => 'submit',
        '#name' => 'add-'.$i,
        '#value' => $this->t('+ Add +'),
        '#submit' => ['::add1SubmitCallback'],
        '#ajax' => [
          'callback' => '::add1Callback',
          'wrapper' => 'fieldset-wrapper-'.$i,
          // 'effect' => 'slide',
        ],
      ];

      $form['names_fieldset'][$i]['remove_section'] = [
        '#type' => 'submit',
        '#name' => $i,
        '#value' => $this->t('++ Remove section ++'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::removeXCallback',
          'wrapper' => 'names-fieldset-wrapper',
          
        ],
        // '#submit' => ['::add1SubmitCallback'],
        // '#ajax' => [
        //   'callback' => '::add1Callback',
        //   'wrapper' => 'names-fieldset-wrapper-'.$i,
        // ],
      ];
      /*
      $form['names_fieldset'][$i]= array(
        '#type' => 'details',
        '#title' => $i . ' C',
        '#open' => TRUE,
        // '#prefix' => '<div id="names-fieldset-wrapper-'.$i.'">',
        //   '#suffix' => '</div>',
      );
      $form['names_fieldset'][$i]['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
        '#value' => $v,
        // '#default_value' => $v,
        // '#prefix' => "<div id='update-name-value-".$i."'>",
        // '#suffix' => "</div>",
      ];

      $form['names_fieldset'][$i]['actions'] = [
        '#type' => 'submit',
        '#name' => $i,
        '#value' => $this->t('Remove one'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::removeXCallback',
          'wrapper' => 'names-fieldset-wrapper',
        ],
      ];
      */
    }

    // $form['names_fieldset']['actions'] = [
    //   '#type' => 'actions',
    // ];
    // $form['names_fieldset']['actions']['add_name'] = [
    //   '#type' => 'submit',
    //   '#name' => 88888,
    //   '#value' => $this->t('Add one more'),
    //   '#submit' => ['::addOne'],
    //   '#ajax' => [
    //     'callback' => '::addmoreCallback',
    //     'wrapper' => 'names-fieldset-wrapper',
    //   ],
    // ];
    // If there is more than one name, add the remove button.
    // if ($num_names > 1) {
      // $form['names_fieldset']['actions']['remove_name'] = [
      //   '#type' => 'submit',
      //   '#value' => $this->t('Remove one'),
      //   '#submit' => ['::removeCallback'],
      //   '#ajax' => [
      //     'callback' => '::addmoreCallback',
      //     'wrapper' => 'names-fieldset-wrapper',
      //   ],
      // ];
    // }

    // $form_state->setCached(FALSE);
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_api_example_ajax_addmore';
  }

  public function add1Callback(array &$form, FormStateInterface $form_state) {
    dpm('add1Callback');
    $last_nid = $form_state->getTriggeringElement()['#name'];

    $last_nid = explode('-', $last_nid);
    if(strcmp($last_nid[0], 'add') == 0){
      return $form['names_fieldset'][$last_nid[1]];
    }else if(strcmp($last_nid[0], 'remove') == 0){ // remove
      return $form['names_fieldset'][$last_nid[1]];
    }else{
      return $form['names_fieldset'];
    }
    
  }

  public function removeXCallback(array &$form, FormStateInterface $form_state) {
    dpm('removeXCallback');
    $last_nid = $form_state->getTriggeringElement()['#name'];
    // dpm($last_nid);
    $last_nid = explode("-", $last_nid);

    return $form['names_fieldset'];
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    dpm('addmoreCallback');
    // $last_nid = $form_state->getTriggeringElement()['#name'];
    // dpm($last_nid);
    return $form['names_fieldset'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    dpm('addOne');
    /*
    $last_nid = $form_state->getTriggeringElement()['#name'];
    // dpm($last_nid);

    $name_field = $form_state->get('num_names');
    $add_button = $name_field + 1;
    $form_state->set('num_names', $add_button);
    // Since our buildForm() method relies on the value of 'num_names' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state->setRebuild();
    */

    $test = $form_state->get('test');

    $test[] = rand();
    // dpm($test);

    $form_state->set('test', $test);

    $form_state->setRebuild();
  }

  public function add1SubmitCallback(array &$form, FormStateInterface $form_state) {
    dpm('add1SubmitCallback');
    $last_nid = $form_state->getTriggeringElement()['#name'];
    
    $test = $form_state->get('test');

    $last_nid = explode('-', $last_nid);

    
    if(strcmp($last_nid[0], 'add') == 0){
      $m = $test[$last_nid[1]];
      $m[] = array("name"=>rand());
  
      $test[$last_nid[1]] = $m;
    }else if(strcmp($last_nid[0], 'remove') == 0){
      $m = $test[$last_nid[1]];
      unset($m[$last_nid[2]]);
      // dpm($last_nid);
      // dpm($m);
  
      $test[$last_nid[1]] = $m;
    }else if(strcmp($last_nid[0], 'all') == 0){
      unset($test[$last_nid[1]]);
    }

    dpm('add1SubmitCallback');

    $form_state->set('test', $test);
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    dpm("removeCallback");
    $last_nid = $form_state->getTriggeringElement()['#name'];
    
    $test = $form_state->get('test');

    // $last_nid = explode("-", $last_nid);

    // $m = $test[$last_nid[0]];
    unset($test[$last_nid]);
    // dpm($last_nid);
    // dpm($m);

    // $test[$last_nid[0]] = $m;
    // unset($test[$last_nid[1]]);
    // dpm($test);
    $form_state->set('test', $test);

    dpm($test);
    

    /*
    // $test[$last_nid] = $test[$last_nid];
    unset($test[$last_nid]);
    $form_state->set('test', $test);
    */

    // $form['names_fieldset'][$i]
    // unset($form['names_fieldset'][$last_nid]);

    // dpm($form_state->get('test'));
    
    // $name_field = $form_state->get('num_names');
    // // if ($name_field > 1) {
    //   $remove_button = $name_field - 1;
    //   $form_state->set('num_names', $remove_button);
    // }
    // Since our buildForm() method relies on the value of 'num_names' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state->setRebuild();
  }

  /**
   * Final submit handler.
   *
   * Reports what values were finally set.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue(['names_fieldset', 'name']);

    $output = $this->t('These people are coming to the picnic: @names', [
      '@names' => implode(', ', $values),
    ]
    );
    $this->messenger()->addMessage($output);
  }

}