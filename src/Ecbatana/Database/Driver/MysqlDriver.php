<?php
namespace Ecbatana\Database\Driver;

use PDO;

class MysqlDriver
{
	/**
	 * This variable is used to hold the dsn
	 * 
	 * @var string
	 */
	
	static private $dsn;

	/**
	 * This variable is used to hold the mysql credential
	 * 
	 * @var TODO
	 */
	
	static private $credential;

	/**
	 * This method is used to set dsn of mysql
	 * 
	 * @return string on success and false on failure
	 */
		
	static public function setDsn($database, $host)
	{
		self::$dsn = 'mysql:dbname=' . $database . ';host=' . $host;
	}

	/**
	 * This method is used to set the mysql credential
	 * 
	 * @return TODO
	 */
	
	static public function setCredential($credential)
	{
		self::$credential = $credential;
	}

	/**
	 * This method is used to set the mysql connection
	 * 
	 * @return TODO
	 */
	
	static public function setConnection()
	{
		$dbc = new PDO(self::$dsn, self::$credential['user'], self::$credential['pass']);
		return $dbc;
	}

}