<?php
/**
 * Created by PhpStorm.
 * User: skorta
 * Date: 3/15/2018
 * Time: 6:23 PM
 *
 */
require 'contacts.php';

$sql = ContactList::selectToDropDown();

$data['data'] = $sql;

echo json_encode($data);

?>