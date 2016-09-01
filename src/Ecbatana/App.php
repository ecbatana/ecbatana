<?php
namespace Ecbatana;

use Ecbatana\Pipe\Pipe as Pipe;

class App extends Pipe
{

	public function run($config)
	{
		$this->pipe($config);
	}

	public function error()
	{
		return false;
	}
}