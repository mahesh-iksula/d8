<?php
/**
 * @file
 * Contains \Drupal\hello_world\Form\HelloForm.
 */
namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
    $form['mobile_number'] = array(
      '#type' => 'tel',
      '#title' => $this->t('Your Mobile number'),
      '#maxlength' => 10,
      '#size' => 15,
    );
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
    dpm($form_state, "validateFormState");
    if (strlen($form_state->getValue('mobile_number')) !== 10) {
      $form_state->setErrorByName('mobile_number', $this->t('Please Enter Valid Mobile number'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    dpm($form, "submitForm");
    dpm($form_state, "submitFormState");
    drupal_set_message($this->t('Your Mobile number is @number', array('@number' => $form_state->getValue('mobile_number'))));
  }

}
?>