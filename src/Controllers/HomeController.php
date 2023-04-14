<?php

namespace src\Controllers;

use src\Models\News;

require_once 'MainController.php';
require_once __DIR__ .'/../Models/News.php';

class HomeController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Отображение списка новостей
	 */
	public function index()
	{
		$news = new News();
		$content['news'] = $news->listNews();

		return $this->getContent($content);
	}

	/**
	 * Отображение выбранной новости
	 */
	public function show(int $id)
	{
		$news = new News();
		$content['new'] = $news->getNews($id);

		return $this->getContent($content);
	}
}
