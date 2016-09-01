<?php
namespace Ecbatana\Container;

use Ecbatana\Config\Config as Config; // 1
use Ecbatana\Controller\Controller as Controller; // 4
use Ecbatana\Controller\ControllerLoader as Loader; // 4
use Ecbatana\Database\Database as Database; // 5
use Ecbatana\Eloquent\Eloquent as Eloquent; // 7
use Ecbatana\Model\Model as Model; // 6
use Ecbatana\Request\Request as Request; // 3
use Ecbatana\Router\Router as Route; // 2
use Ecbatana\Session\Session as Session; // 9
use Ecbatana\Url\Url as Url; // 10
use Ecbatana\Validator\Validator as Validator; // 8

class Container
{
	/**
	 * Variable contains database class
	 * 
	 * @var TODO
	 */
	
	private $database;

	/**
	 * Variable contains controller class
	 * 
	 * @var TODO
	 */
	
	private $controller;

	/**
	 * This method is used to run the initiator.
	 * 
	 * @return void
	 */
	
	public function run()
	{
		session_start();
		$this->classInitiator();
		$this->methodInitiator();
		$this->manualLoadInitiator();
	}

	/**
	 * This method is used to call the database class
	 * 
	 * @return void
	 */
	
	private function classInitiator()
	{
		$this->database = new Database;
	}

	/**
	 * This method is used to run the method initiator.
	 * 
	 * @return void
	 */

	private function methodInitiator()
	{
		$this->database->run();
		Request::run();
	}

	/**
	 * This method is used the require the setter
	 * 
	 * @return void;
	 */

	private function manualLoadInitiator()
	{
		require(Config::$url['routes']);
	}
}