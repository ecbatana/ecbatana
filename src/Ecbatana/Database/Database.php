<?php
namespace Ecbatana\Database;

use PDO;
use Ecbatana\Config\Config as Config;
use Ecbatana\Database\Driver\MysqlDriver as MysqlDriver;

class Database extends QueryBuilder
{
	/**
	 * Variables to hold the database instance
	 * 
	 * @var obj
	 */
	
	private $dbc;

	/**
	 * Variables to hold the database driver configuration
	 * 
	 * @var string
	 */

	private $driver;

	/**
	 * Variables to hold the database name configuration
	 * 
	 * @var string
	 */
	
	private $dbname;

	/**
	 * Variables to hold the database host configuration
	 * 
	 * @var string
	 */
	
	private $host;

	/**
	 * Variables to hold the user database credential
	 * 
	 * @var array
	 */
	
	private $credential;

	public function __construct()
	{
		$this->run();
	}

	/**
	 * This method is initiator 
	 * 
	 * @return TODO
	 */
	
	public function run()
	{
		$conf = Config::$database;
		$this->driver = $conf['driver'];
		$this->dbname = $conf['database'];
		$this->host = $conf['host'];
		$this->credential = array(
			'user' => $conf['username'],
			'pass' => $conf['password']
		);
		$this->setConnection();
	}

	/**
	 * This method is used to set the connection
	 * 
	 *
	 */
	
	private function setConnection()
	{
		switch ($this->driver) {
			case 'mysql':
				MysqlDriver::setDsn($this->dbname, $this->host);
				MysqlDriver::setCredential($this->credential);
				$this->dbc = MysqlDriver::setConnection();
				$this->dbc->setAttribute(
					PDO::ATTR_ERRMODE, 
					PDO::ERRMODE_EXCEPTION
				);
				break;
			
			default:
				# code...
				break;
		}
	}

	/**
	 * Method used to get the db instance
	 * 
	 * @return void
	 */
	
	public function getDB()
	{
		return $this->dbc;
	}
}