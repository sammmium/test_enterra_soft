<?php

namespace src;

use \Twig\Loader\FilesystemLoader as Loader;
use \Twig\Environment as Twig;

class App
{
	protected array $config;

	public function setConfig(array $config = []): void
	{
		$this->config = $config;
	}

	public function getMenu(): array
	{
		$menu = [];

		foreach ($this->config['sections'] as $section) {
			if ($section['enabled']) {
				$menu[] = $section;
			}
		}

		return $menu;
	}

	public function run(): string
	{
		$loader = new Loader('src/Views');
		$loader->addPath('public/', 'public');
		$view = new Twig($loader);

		$routes = require_once __DIR__ . '/routes.php';
		$router = new \src\Router();
		$router->setParams($_SERVER['REQUEST_URI'], $routes, $this->config);
		$executor = $router->init();

		return  $view->render($executor['name'], $executor['attributes']);
	}
}
