<?php

require 'contacts.php';

$sql = ContactList::selectAll();

$data['data'] = $sql;

echo json_encode($data);

?>
