<?php

namespace src\Controllers;

require_once 'MainController.php';

class SubjectsController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function index()
	{
		return [
			'app_name' => $this->config['app_name'],
			'menus' => $this->getMenu()
		];
	}
}
