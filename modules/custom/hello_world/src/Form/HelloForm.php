<?php
/**
 * @file
 * Contains \Drupal\hello_world\Form\HelloForm.
 */
namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
/**
 * Implements an Hello form.
 */
class HelloForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hello_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['full_name'] = array(
      '#type' => 'textfield',
      '#title' => 'Full Name',
      '#size' => 30,
      '#required' => TRUE,
        //'#default_value' => \Drupal::state()->get('full_name') ?: '',
    );
    $form['age'] = array(
      '#type' => 'textfield',
      '#title' => 'Age',
      '#size' => 10,
      '#required' => TRUE,
        //'#default_value' => \Drupal::state()->get('age') ?: '',
    );
    $form['mobile_number'] = array(
      '#type' => 'tel',
      '#title' => $this->t('Your Mobile number'),
      '#maxlength' => 10,
      '#size' => 15,
      '#required' => TRUE,
    );
    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Your .com email address.'),
      //'#default_value' => $config->get('demo.email_address'),
      '#ajax' => [
        'callback' => array($this, 'validateEmailAjax'),
        'event' => 'change',
        'progress' => array(
          'type' => 'throbber',
          'message' => t('Verifying email...'),
        ),
      ],
      '#suffix' => '<span class="email-valid-message"></span>'
    );
//    $city_options = array(0 => 'Select', 1 => 'Mumbai', 2 => 'Delhi');
//    $selected = ($form_state->getValue('selected_city') !== null) ?: key($city_options);
//
//    if (empty($selected))
//      $has_dis = TRUE;
//    else
//      $has_dis = FALSE;
//    
//    $form['selected_city'] = array(
//      '#type' => 'select',
//      '#title' => $this->t('City'),
//      '#options' => $city_options,
//      '#required' => FALSE,
//      '#ajax' => array(
//        'event' => 'change',
//        'callback' => array($this, 'area_list_dropdown_callback'),
//        'wrapper' => 'dropdown-second-replace',
//      ),
//    );
//    $form['dropdown_second'] = array(
//      '#type' => 'select',
//      '#title' => $city_options[$selected] . ' ' . $this->t('Area List'),
//      '#prefix' => '<div id="dropdown-second-replace">',
//      '#suffix' => '</div>',
//      '#disabled' => $has_dis,
//      '#options' => $this->get_area_dropdown_options($selected),
//      //'#default_value' => isset($form_state['values']['dropdown_second']) ? $form_state['values']['dropdown_second'] : '',
//    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
//    dpm($form, "validateForm");
//    dpm($form_state, "validateFormState");
    if (!preg_match("/^[a-zA-Z ]*$/", $form_state->getValue('full_name'))) {
      $form_state->setErrorByName('full_name', $this->t('Please Enter Letters only in Full Name Field.'));
    }
    if (!is_numeric($form_state->getValue('age'))) {
      $form_state->setErrorByName('age', $this->t('Please Enter Only Numeber in Age Field.'));
    }
    if (strlen($form_state->getValue('mobile_number')) !== 10) {
      $form_state->setErrorByName('mobile_number', $this->t('Please Enter Valid Mobile number'));
    }
    if (!$this->validateEmail($form, $form_state)) {
      $form_state->setErrorByName('email', $this->t('This is not a .com email address.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//    dpm($form, "submitForm");
    dpm($form_state, "submitFormState");
    $nid = db_insert('person_details')
      ->fields(array(
        'full_name' => $form_state->getValue('full_name'),
        'age' => $form_state->getValue('age'),
        'mobile_number' => $form_state->getValue('mobile_number'),
        'email_id' => $form_state->getValue('email'),
      ))
      ->execute();
    drupal_set_message($this->t("Form Details Save Successfully."));
    //drupal_set_message($this->t('Your Mobile number is @number', array('@number' => $form_state->getValue('mobile_number'))));
  }
  
  protected function validateEmail(array &$form, FormStateInterface $form_state) {
    if (substr($form_state->getValue('email'), -4) !== '.com') {
      return FALSE;
    }
    return TRUE;
  }
  
  protected function area_list_dropdown_callback(array &$form, FormStateInterface $form_state) {
    dpm("inside area_list_dropdown_callback");
    return $form['dropdown_second'];
  }
  
  protected function get_area_dropdown_options($key = '') {
    dpm($key, "get_area_dropdown_options");
  }

  /**
   * Ajax callback to validate the email field.
   */
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    $valid = $this->validateEmail($form, $form_state);
    $response = new AjaxResponse();
    if ($valid) {
      $css = ['border' => '1px solid green'];
      $message = $this->t('Email ok.');
    }
    else {
      $css = ['border' => '1px solid red'];
      $message = $this->t('Email not valid.');
    }
    $response->addCommand(new CssCommand('#edit-email', $css));
    $response->addCommand(new HtmlCommand('.email-valid-message', $message));
    return $response;
  }

}

?>