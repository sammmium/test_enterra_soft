<?php

namespace src\Controllers;

use src\Models\News;

require 'MainController.php';
include __DIR__ .'/../Models/News.php';

class HomeController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	private function testData()
	{
		return [
			'news' => [
				[
					'id' => 1,
					'title' => 'Новость 1',
					'description' => 'происшествие 1 произошло',
					'create_at' => '2023-04-12'
				],
				[
					'id' => 2,
					'title' => 'Новость 2',
					'description' => 'происшествие 2 произошло',
					'create_at' => '2023-04-13'
				]
			]
		];
	}

	public function index()
	{
		$news = new News();
		$content['news'] = $news->listNews();

		return $this->getContent($content);
	}

	public function show(int $id)
	{
		$news = new News();
		$content['new'] = $news->getNews($id);

		return $this->getContent($content);
	}
}
