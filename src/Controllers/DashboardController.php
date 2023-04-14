<?php

namespace src\Controllers;

use src\Models\News;

require_once 'MainController.php';
require_once __DIR__ .'/../Models/News.php';

class DashboardController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function index()
	{
		$news = new News();
		$content['news'] = $news->listNews();

		return $this->getContent($content);
	}

	public function create()
	{
		return $this->getContent();
	}

	public function save()
	{
		$rules = [
			'title' => ['type' => 'text', 'required' => true],
			'description' => ['type' => 'text', 'required' => true],
			'value' => ['type' => 'text', 'required' => true]
		];

		$data = $_POST;
		$storedId = null;
		$input['news'] = $data;

		if ($this->validate($data, $rules)) {
			$newsModel = new News();
			if (!$newsModel->hasNews($data)) {
				$storedId = $newsModel->storeNews($data);
			}
		}

		if ($storedId) {
			// достать данные из БД
			$newsModel = new News();
			$input['news'] = $newsModel->getNews($storedId);

			// отрисовать форму редактирования
			header('Location: /admin');
			exit;
		}
		
		// несохраненные данные впихнуть в форму редактирования
		return $this->getContent($data);
	}

	public function edit(int $id)
	{
		$news = new News();
		$content['news'] = $news->getNews($id);

		return $this->getContent($content);
	}

	public function show(int $id)
	{
		$news = new News();
		$content['news'] = $news->getNews($id);

		return $this->getContent($content);
	}

	public function update()
	{
		$rules = [
			'id' => ['type' => 'numeric', 'required' => true],
			'title' => ['type' => 'text', 'required' => true],
			'description' => ['type' => 'text', 'required' => true],
			'value' => ['type' => 'text', 'required' => true]
		];

		$data = $_POST;
		
		$input['news'] = $data;

		if ($this->validate($data, $rules)) {
			$newsModel = new News();
			$newsModel->updateNews($data);
		}

		header('Location: /admin');
		exit;
	}

	public function delete(int $id)
	{
		$newsModel = new News();
		$newsModel->deleteNews($id);

		header('Location: /admin');
		exit;
	}
}
