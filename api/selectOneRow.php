<?php

require 'contacts.php';


//сheck which value sent from ajax if 1 update idUsed to Used if 2 to isNotUsed
$check = $_POST["check"];

if($_REQUEST['check']) {
    if($check == 1) {
        $sql = ContactList::selectOneUsed();

        $id = $sql['idContact'];

        if (!empty($id)) {
            $isUsed = 2;
            ContactList::UpdateIsUsed($id, $isUsed);
        }
    } else if($check == 2) {
        $sql = ContactList::selectOneNotUsed();

        $id = $sql['idContact'];

        if (!empty($id)) {
            $isUsed = 1;
            ContactList::UpdateIsUsed($id, $isUsed);
        }
    }
}
echo json_encode($check);

?>
