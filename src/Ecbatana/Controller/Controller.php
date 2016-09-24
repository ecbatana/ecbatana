<?php
namespace Ecbatana\Controller;

use Ecbatana\Database\Database as Database;
use Ecbatana\Controller\Loader as Loader;

class Controller
{
	/**
	 * Variable to hold the database instance
	 * 
	 * @var obj
	 */

	private $db;

	/**
	 * Variable to hold the loader instance
	 * 
	 * @var obj
	 */
	
	protected $load;

	/**
	 * Variable to hold the model list
	 * 
	 * @var array
	 */
	
	private $modelList = array();

	/**
	 * This method is used to run the load function from Loader class
	 * 
	 * @return TODO
	 */

	protected function load($action, $path, $data = null)
	{
		$this->load = new Loader;
		$this->db = new Database;
		$this->db->run();

		switch ($action) {
			case 'view':
				$view = $path;
				$this->load->view($view, $data);
				break;
			
			case 'model':
				$model = $path;
				if ($this->load->model($model) === true) {
					$this->{$model} = new $model;
				}
				break;

			default:
				# code...
				break;
		}
	}
}