<?

/**
 * MySQL database class
 *
 * Used by all modules to work with MySQL database
 *
 * @package framework
 * @version 1.1b
 */
class mysql
{

    /**
     * @var string database host
     * @access private
     */
    var $host;

    /**
     * @var string database port
     * @access private
     */
    var $port;

    /**
     * @var string database access username
     * @access private
     */
    var $user;

    /**
     * @var string database access password
     * @access private
     */
    var $password;

    /**
     * @var string database name
     * @access private
     */
    var $dbName; // database name

    /**
     * @var int database handler
     * @access private
     */
    var $dbh;

// --------------------------------------------------------------------
    /**
     * MySQL constructor
     *
     * used to create mysql database object and connect to database
     *
     * @param string $host mysql host
     * @param string $port mysql port
     * @param string $user mysql user
     * @param string $password mysql password
     * @param string $database mysql database name
     * @access public
     */
    function mysql($host, $port, $user, $password, $database)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->dbName = $database;
        $this->Connect();
    }

// --------------------------------------------------------------------
    /**
     * Connects to database
     *
     * @return bool connection result (1 - success, 0 - failed)
     * @access private
     */
    function Connect()
    {
        // connects to database
        $this->dbh = @mysql_connect($this->host . ":" . $this->port, $this->user, $this->password);

        $this->ChooseDb($this->dbName);

    }

    public function ChooseDb($dbName)
    {
        if (!@mysql_select_db($dbName, $this->dbh)) {
            $this->Error();
            return 0;
        } else {
            mysql_query("SET NAMES 'utf8';", $this->dbh);
            mysql_query("SET CHARACTER SET 'utf8';", $this->dbh);
            mysql_query("SET SESSION collation_connection = 'utf8_general_ci';", $this->dbh);
            return $this;
        }
    }

// --------------------------------------------------------------------
    /**
     * Execute SQL SELECT query and return first record
     *
     * This method returns record assosiated array (by field names)
     *
     * @param string $query SQL SELECT query
     * @return array execution result
     * @access public
     */
    function SelectOne($query)
    {
        if ($result = mysql_query($query, $this->dbh)) {
            $rec = mysql_fetch_array($result, MYSQL_ASSOC);
            /*
            if (Is_Array($rec)) {
                foreach(array_keys($rec) as $k) {
                    if (is_numeric($k)) {
                        UnSet($rec[$k]);
                    }
                }
            }
            */
            return $rec;
        } else {
            $this->Error($query);
        }
    }


    // --------------------------------------------------------------------
    /**
     * Execute SQL SELECT query and return all records
     * @param string $query SQL SELECT query
     * @return array execution result
     */

    function selectAll($query)
    {
          $result = mysql_query($query);
          while($row = mysql_fetch_array($result)){
               $json[] = $row;
          }
          return $json;
    }
// --------------------------------------------------------------------
    /**
     * Execute SQL UPDATE query for one record
     *
     * Record is defined by assosiated array
     *
     * @param string $table table to update
     * @param string $data record to update
     * @param string $ndx index field (used in WHERE part of SQL request)
     * @access public
     */
    function Update($table, $data, $ndx = "idContact")
    {

        $qry = "UPDATE `$table` SET ";
        foreach ($data as $field => $value) {
            if (!is_Numeric($field)) {
                $qry .= "`$field`='" . $this->DBSafe1($value) . "', ";
            }
        }
        $qry = substr($qry, 0, strlen($qry) - 2);
        $qry .= " WHERE $ndx='" . $data[$ndx] . "'";

        if (!mysql_query($qry, $this->dbh)) {
            $this->Error($qry);
            return 0;
        }
        return 1;
    }

// --------------------------------------------------------------------
    /**
     * Execute SQL INSERT query for one record
     *
     * Record is defined by assosiated array
     *
     * @param string $table table for new record
     * @param string $data record to insert
     * @return execution result (0 - if failed, INSERT ID - if succeed)
     * @access public
     */
    function Insert($table, &$data)
    {
        $fields = "";
        $values = "";
        foreach ($data as $field => $value) {
            if (!is_Numeric($field)) {
                $fields .= "`$field`, ";
                $values .= "'" . $this->DBSafe1($value) . "', ";
            }
        }
        $fields = substr($fields, 0, strlen($fields) - 2);
        $values = substr($values, 0, strlen($values) - 2);
        $qry = "INSERT INTO `$table`($fields) VALUES($values)";
        if (!mysql_query($qry, $this->dbh)) {
            $this->error($qry);
            return 0;
        }
        return mysql_insert_id($this->dbh);
    }

// --------------------------------------------------------------------
    function DbSafe1($str)
    {
        $str = mysql_real_escape_string($str);
        return $str;
    }

// --------------------------------------------------------------------
    /**
     * MySQL database error handler
     *
     * @param string $query used query string
     * @access private
     */
    function Error($query = "")
    {
        echo mysql_errno() . ": " . mysql_error() . "<br>$query";
        return 1;
    }
}

// --------------------------------------------------------------------
/**
 * Execute SQL SELECT query and return first record
 *
 * This function returns record assosiated array (by field names)
 *
 * @param string $query SQL SELECT query
 * @global object mysql database object
 * @return array execution result
 */
function SQLSelectOne($query)
{
    global $db;
    return $db->SelectOne($query);
}

// --------------------------------------------------------------------
/**
 * Execute SQL SELECT query and return first record
 *
 * This function returns record assosiated array (by field names)
 *
 * @param string $query SQL SELECT query
 * @global object mysql database object
 * @return array execution result
 */
function SQLSelectAll($query)
{
    global $db;
    return $db->selectAll($query);
}


// --------------------------------------------------------------------
/**
 *  @param sql string
 * @return number of rows all records
 */
function SQLNumOfRow($result) {
    $nor = mysql_query($result);
    $nor_result = mysql_num_rows($nor);
    return $nor_result;
}



// --------------------------------------------------------------------
/**
 * @param  $sql request for DELETE
 */

function SQLDelete($sql) {
    mysql_query($sql);
}



// --------------------------------------------------------------------
/**
 * Execute SQL INSERT query for one record
 *
 * Record is defined by assosiated array
 *
 * @param string $table table for new record
 * @param string $record record to insert
 * @global object mysql database object
 * @return execution result (0 - if failed, INSERT ID - if succeed)
 */
function SQLInsert($table, &$record)
{
    global $db;
    return $db->Insert($table, $record);
}

// --------------------------------------------------------------------
/**
 * Execute SQL UPDATE query for one record
 *
 * Record is defined by assosiated array
 *
 * @param string $table table to update
 * @param string $record record to update
 * @global object mysql database object
 */
function SQLUpdate($table, $record, $ndx = 'idContact')
{
    global $db;
    return $db->Update($table, $record, $ndx);
}
