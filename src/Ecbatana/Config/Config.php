<?php
namespace Ecbatana\Config;

class Config
{
	/**
	 * Variable Contains Environment Configuration.
	 * 
	 * @var string
	 */

	static public $environment;

	/**
	 * Variable contains application timezone.
	 * 
	 * @var string
	 */

	static public $timezone;

	/**
	 * Variable contains database configurations.
	 * 
	 * @var array
	 */

	static public $database;

	/**
	 * Variable contains url configurations.
	 * 
	 * @var array
	 */
	
	static public $url;

	/**
	 * This method to receive config data and parse to distribute
	 * into local variables.
	 * 
	 * @return void
	 */

	public function receive($config)
	{
		$this->parse($config);
	}

	/**
	 * This method is used to parse and distribute into local variables.
	 * 
	 * @return void
	 */

	private function parse($config)
	{
		self::$environment = $config['environment'];
		self::$timezone = $config['timezone'];
		self::$database = $config['database'];
		self::$url = $config['url'];
	}
}