<?php
require_once('dbcredential.php');
/**
 * MySQLi database; only one connection is allowed per database
 * pre-requisite : implement logMsg function which is logger function as here we are using internal logger to log rather then echo- ying in the terminal.
 */
class Database
{
    private $_connection;

    // Store the single instance.
    private static $_instance = array();
    /**
     * check_connection_alive pings mysqli server and checks if connection is alive
     * @param int $link mysql connection url
     * @return bool returns true if connection is alive or false if not
     */
    public static function check_connection_alive($link)
    {
        /* check if server is alive */
        if(mysqli_ping($link))
        {
            //connection is ok 
            return true;
        }
        else
        {
            //connection is timed - out
            return false;
        }
    }
    /**
     * Get an instance of the Database.
     * @return Database 
     */
    public static function getInstance($db_name, $index = 0)
    {
        //key to identify database uniquley
        $uid = $db_name."_".$index;
        if (!array_key_exists($uid, self::$_instance))
        {
            self::$_instance[$uid] = new self($db_name, $index);
        }
        else
        {
            //check for old connection is alive or not. new connection will work so only checking for old connectin.
            if(self::check_connection_alive(self::$_instance[$uid]->_connection))
            {
                //do nothing as of now
            }
            else
            {
                //create new connection - instance of database object if connection is not alive
                self::$_instance[$uid] = new self($db_name, $index);
            }
        }
        return self::$_instance[$uid];
    }
    /**
     * Constructor.
     */
    public function __construct($db_name, $index)
    {
        global $databases;
        $mysqli = new mysqli($databases[$db_name][$index]["host"], $databases[$db_name][$index]["username"], $databases[$db_name][$index]["password"], $databases[$db_name][$index]["database"]);
        // Error handling.
        if(mysqli_connect_error())
        {
            logMsg("connection to database ".$db_name." with index $index cannot created ". mysqli_connect_error(), 1);
        }
        else
        {
            $this->_connection = $mysqli;
        }
    }

    /**
     * Empty clone magic method to prevent duplication. 
     */
    private function __clone() {}

    /**
     * Get the mysqli connection. 
     */
    public function getConnection()
    {
        return $this->_connection;
    }
}