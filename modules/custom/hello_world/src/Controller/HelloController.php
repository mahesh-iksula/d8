<?php
/**
 * @file
 * Contains \Drupal\hello_world\Controller\HelloController.
 */

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;

class HelloController extends ControllerBase {

  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => $this->t('Hello, World!'),
    );
  }

  public function display_person_contents() {
    $header = array(
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      array(
        'data' => t('Id'),
        'field' => 't.id',
      ),
      array(
        'data' => t('Name'),
        'field' => 't.full_name',
      ),
      array(
        'data' => t('age'),
        'field' => 't.age',
      ),
      array(
        'data' => t('mobile_number'),
        'field' => 't.mobile_number',
      ),
      array(
        'data' => t('email_id'),
        'field' => 't.email_id',
      ),
    );
    $db = \Drupal::database();
    $result = $db->select('person_details','a')->fields('a')->execute();
    dpm($result);
//    $query = db_select('person_details', 't');
//        //->extend('TableSort'); // Using the TableSort Extender is what tells the
//    // the query object that we are sorting.
//    $query->fields('t');
//
//    $result = $query
//        ->orderByHeader($header) // Don't forget to tell the query object how to
//        // find the header information.
//        ->execute();

    $rows = array();
    foreach ($result as $row) {
      // normally we would add some nice formatting to our rows
      // but for our purpose we are simply going to add our row
      // to the array.
      $rows[] = array('data' => (array) $row);
    }

    // build the table for the nice output.
    $build['tablesort_table'] = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    );

    return $build;
  }

}

?>