<?php
namespace Ecbatana\Pipe;

use Ecbatana\Config\Config as Config;
use Ecbatana\Container\Container as Container;

class Pipe
{
	protected function pipe($config)
	{
		// IN
		$this->distribute($config);

		// OUT
		$this->run();
	}

	private function run()
	{
		$container = new Container;
		$container->run();
	}

	private function distribute($config)
	{
		$send = new Config;
		$send->receive($config);
	}
}