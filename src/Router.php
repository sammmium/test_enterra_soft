<?php

namespace src;

use src\Controllers\DashboardController;

class Router
{
	protected string $path;

	protected array $routes;

	protected array $config;

	protected array $route = [
		'path' => '/',
		'controller' => 'HomeController',
		'action' => 'index',
		'view' => 'home/index.twig',
		'selected_menu_item' => 'home'
	];

	public function setParams(string $request_url, array $routes, array $config): void
	{
		$this->path = $request_url;
		$this->routes = $routes;
		$this->config = $config;
	}

	public function init(): array
	{
		$result = [];

		$id = 0;
		foreach ($this->routes as $path => $data) {
			$clearPath = '';
			$pos = strpos($path, '{id}');
			if ($pos !== false) {
				$id = mb_substr($this->path, $pos);
				$clearPath = mb_substr($this->path, 0, $pos);
				if ($clearPath === mb_substr($path, 0, $pos)) {
					$this->route = [];
					$this->route['path'] = $clearPath;
					$this->route += $data;
					break;
				}
			} else {
				if ($this->path === $path) {
					$this->route = [];
					$this->route['path'] = $path;
					$this->route += $data;
					break;
				}
			}

		}

		require_once 'Controllers/' . $this->route['controller'] . '.php';
		$pathController = '\\src\\Controllers\\' . $this->route['controller'];
		$controller = new $pathController($this->config);
		$method = $this->route['action'];

		$result['attributes'] = ($id > 0) ? $controller->$method($id) : $controller->$method();
		$result['name'] = $this->route['view'];
		$result['attributes']['selected_menu_item'] = $this->route['selected_menu_item'];
		
		return $result;
	}
}
