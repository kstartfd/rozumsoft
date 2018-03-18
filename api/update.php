<?php

require 'contacts.php';

$id  = $_POST["id"];



if($_REQUEST['id']) {
    $isUsed = 2;
    ContactList::UpdateIsUsed($id, $isUsed);
}

echo json_encode($isUsed);
?>
