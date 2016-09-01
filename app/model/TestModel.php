<?php

use Ecbatana\Model\Model as Model;

class TestModel extends Model
{
	protected $table = 'users';

	public function __construct()
	{
		parent::__construct();
	}
}