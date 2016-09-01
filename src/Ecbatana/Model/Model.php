<?php
namespace Ecbatana\Model;

use Ecbatana\Database\Database as Database;

class Model extends Database
{
	public function __construct()
	{
		parent::__construct();
	}

	public function akon()
	{
		echo 'im load from model';
	}
}