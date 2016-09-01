<?php
namespace Ecbatana\Controller;

use Ecbatana\Config\Config as Config;

class Loader
{
	/**
	 * Variable to hold the called viewpath
	 * 
	 * @var string
	 */

	private $viewpath;

	/**
	 * Variable to hold the called modelpath
	 * 
	 * @var string
	 */

	private $modelpath;

	/**
	 * This method is used to require the view
	 * 
	 * @return void
	 */

	public function view($viewpath, $data)
	{
		$this->setViewpath($viewpath);
		unset($viewpath);
		if ($this->checkView($this->viewpath) && $this->checkData($data)) {
			$data;
			require($this->viewpath);
		}
	}

	/**
	 * This method is used to require the view
	 * 
	 * @return void
	 */

	public function model($modelpath)
	{
		$modelname = $modelpath;
		$this->setModelpath($modelpath);
		unset($modelpath);
		if ($this->checkModel($this->modelpath)) {
			require($this->modelpath);
			return true;
		}
	}

	/**
	 * This method is used to set the modelpath
	 * 
	 * @return TODO
	 */
	
	private function setModelpath($modelpath)
	{
		$this->modelpath = Config::$url['model'] . '/' . $modelpath . '.php';
	}

	/**
	 * This method is used to set the viewpath
	 * 
	 * @return TODO
	 */
	
	private function setViewpath($viewpath)
	{
		$this->viewpath = Config::$url['view'] . '/' . $viewpath . '.php';
	}

	/**
	 * This method is used to check if data is array or not and if data is
	 * empty or not.
	 * 
	 * @return true on success and false on failure
	 */

	private function checkData($data)
	{
		return !empty($data) && is_array($data) ? true : false;
	}

	/**
	 * This method is used to check if view is exist or not
	 * 
	 * @return true on success and false on failure
	 */
	
	private function checkView($viewpath)
	{
		return file_exists($viewpath) ? true : false;
	}

	/**
	 * This method is used to check if model is exist or not
	 * 
	 * @return true on success and false on failure
	 */
	
	private function checkModel($modelpath)
	{
		return file_exists($modelpath) ? true : false;
	}
}