<?php
namespace Ecbatana\Router;

use Ecbatana\Config\Config as Config;
use Ecbatana\Request\Request as Request;

class Router
{
	/**
	 * Variables that contains route list
	 * 
	 * @var array
	 */

	static private $list = array();

	/**
	 * This method is used to process route with get request
	 * 
	 * @return TODO
	 */

	static public function get($path, $callback)
	{
		$path = $path;
		$request = 'get';
		$class = self::parseClass($callback);
		$method = self::parseMethod($callback);

		if (self::checkExist($class) === true && 
			self::requestCheck($path) === true) {
			if (self::classCheck($class) === true &&
				self::methodCheck($class, $method) === true) {
				// Run Logging				
				$class = '\\' . $class;
				$route = self::setDataList($path, $request, $class, $method);
				self::setToList($route);

				// Run Callback
				self::getCallback($class, $method);
			}
		}
	}

	/**
	 * This method is used to generate the route list
	 * 
	 * @return array on success and false on failure
	 */

	static private function setDataList($path, $request, $class, $method)
	{
		if (! empty($path) && ! empty($class) && ! empty($method)) {
			$route = array(
				'path' => $path,
				'request' => $request,
				'class' => $class,
				'method' => $method
			);
			return $route;
		} else {
			return false;
		}
	}

	/**
	 * This method is used to set the passed selected route
	 * into route list.
	 *
	 * @return void
	 */

	static private function setToList($route)
	{
		array_push(self::$list, $route);
	}

	/**
	 * This class is used to parse callback into separate variables,
	 * that hold the class and method variables.
	 * 
	 * return var on success and false on failure
	 */
	
	static private function parseClass($callback)
	{
		if (strpos($callback, '@') !== false) {
			list($class, $method) = explode('@', $callback);
			return $class;
		} else {
			return false;
		}
	}

	/**
	 * This class is used to parse callback into separate variables,
	 * that hold the class and method variables.
	 * 
	 * return var on success and false on failure
	 */
	
	static private function parseMethod($callback)
	{
		if (strpos($callback, '@') !== false) {
			list($class, $method) = explode('@', $callback);
			return $method;
		} else {
			return false;
		}
	}

	/**
	 * This method is used to check if selected class is exist or not
	 * 
	 * @return true on success and false if failed
	 */
	
	static private function checkExist($class)
	{
		$classPath = Config::$url['controller'] . '/' . $class . '.php';
		$documentRoot = $_SERVER['DOCUMENT_ROOT'];
		$documentRoot = substr($documentRoot, 0, -6);
		$documentRoot .= substr($classPath, 3);

		if (file_exists($classPath)) {
			self::requireClass($documentRoot);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This method is used to require the selected class if class is
	 * exist.
	 *
	 * @return void
	 */
	
	static private function requireClass($class)
	{
		if (empty(array_search($class, get_required_files()))) {
			require($class);
		}
	}

	/**
	 * This method is used to check if selected class is exist or not.
	 * 
	 * @return true on success and false on failure
	 */

	static private function classCheck($class)
	{
		$class = '\\' . $class;
		if (class_exists($class)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This method is used to check if selected method is exist in a
	 * selected class or not.
	 *
	 * @return true on success and false on failure
	 */
	
	static private function methodCheck($class, $method)
	{
		$class = '\\' . $class;
		if (method_exists($class, $method)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This method is used to check if selected route path is same as
	 * request path.
	 * 
	 * @return true on success and false on failure
	 */
	
	static private function requestCheck($path)
	{
		$request = Request::$request;
		if ($path === $request) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This method is used to call the callbacks
	 * 
	 * @return void
	 */
	
	static private function getCallback($class, $method)
	{
		call_user_func(array(new $class, $method));
	}
}