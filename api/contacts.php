
<?php

require 'config.php';
require 'mysql.class.php';

set_time_limit(0);

$db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); // connecting to database

class ContactList
{
    const CONTACT_TABLE = 'contacts';
    const STATUS_TABLE = 'status';
    const IS_USED_TABLE = 'isUsed';

    static function clearText($text)
    {
        $d = explode('©', $text);
        $text = $d[0];

        $text = str_replace(array("\r\n"), "", $text);
        $text = trim($text);
        return $text;
    }

    //Send query to SQLSelectAll() method and data result
    public static function selectAll() {

      $sql = 'SELECT ' . self::CONTACT_TABLE . '.contactName, ' . self::STATUS_TABLE . '.statusName, ' . self::IS_USED_TABLE . '.usedStatus FROM ' . self::CONTACT_TABLE .
            ' INNER JOIN ' . self::STATUS_TABLE . ' ON ' . self::CONTACT_TABLE . '.idStatus = ' . self::STATUS_TABLE . '.idstatus 
              INNER JOIN ' . self::IS_USED_TABLE . ' ON ' . self::CONTACT_TABLE . '.idUsed = ' . self::IS_USED_TABLE . '.idUsed ORDER BY '
            . self::CONTACT_TABLE . '.idContact ASC';

      $exists = SQLSelectAll($sql);
      return $exists;
    }

    //select to dropdown
    public static function selectToDropDown() {
        $sql = 'SELECT ' . self::CONTACT_TABLE . '.contactName, ' . self::CONTACT_TABLE . '.idContact  FROM ' . self::CONTACT_TABLE .
        ' WHERE ' . self::CONTACT_TABLE . '.idUsed = 1';
        $exists = SQLSelectAll($sql);
        return $exists;
    }

    //Update one record

    public static function UpdateIsUsed($id, $isUsed) {
        $data = array('idUsed' => self::clearText($isUsed), 'idContact' => $id);
        SQLUpdate(self::CONTACT_TABLE, $data);
    }

  //select one rand value from tb Contacts when idUsed = 1
    public static function selectOneUsed() {
        $sql = 'SELECT * FROM ' . self::CONTACT_TABLE . ' WHERE idUsed = 1 ORDER BY RAND() LIMIT 1';
        $exists = SQLSelectOne($sql);
        if($exists === false) {
            return null;
        } else {
            return $exists;
        }

    }

    //select one rand value from tb Contacts when idUsed = 2

    public static function selectOneNotUsed() {
        $sql = 'SELECT * FROM ' . self::CONTACT_TABLE . ' WHERE idUsed = 2 ORDER BY RAND() LIMIT 1';
        $exists = SQLSelectOne($sql);
        if($exists === false) {
            return null;
        } else {
            return $exists;
        }

    }

}

?>
