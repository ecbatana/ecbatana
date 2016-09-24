<?php

use Ecbatana\Controller\Controller as Controller;

class HomeController extends Controller
{
	public function __construct()
	{
		$this->load('model', 'TestModel');
	}

	public function index()
	{
		$data = array(
			'title' => 'Ecbatana!'
		);
		$this->load('view', 'home', $data);
	}

	public function test()
	{
		echo 'test OK';
	}
}
