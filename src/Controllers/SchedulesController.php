<?php

namespace src\Controllers;

use src\models\Contacts;
use src\models\Schedules;

require_once 'MainController.php';
include __DIR__ . '/../models/Schedules.php';

class SchedulesController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function index(): array
	{
		$content = [];
		$content['selected_contact'] = null;
		$content['selected_date'] = date('d.m.Y');

		$filter = [
			'contact_id' => $content['selected_contact'],
			'subject_date' => $content['selected_date']
		];

		$model = new Schedules();
		$content += $model->getData($filter);

		$model = new Contacts();
		$content['contact_list'] = $model->getContactList();

		return $this->getContent($content);
	}

	public function select_date(): array
	{
		$content = [];
		$content['selected_date'] = $_POST['datepicker_value'];

		$filter = [
			'contact_id' => $content['selected_contact'],
			'subject_date' => $content['selected_date']
		];

		$model = new Schedules();
		$content += $model->getData($filter);

		$model = new Contacts();
		$content['contact_list'] = $model->getContactList();

		return $this->getContent($content);
	}

	public function lesson_add(): array
	{
		$content = [];
		$content['selected_date'] = $_POST['selected_date'];

		$filter = [
			'subject_date' => $content['selected_date']
		];

		$model = new Schedules();
		$content += $model->getData($filter);

		$model = new Contacts();
		$content['contact_list'] = $model->getContactList();

		return $this->getContent($content);
	}

	public function lesson_create(): void
	{
		$rules = [
			'subject_date' => ['type' => 'text', 'required' => true],
			'contact_id' => ['type' => 'numeric', 'required' => true],
			'subject_time' => ['type' => 'text', 'required' => true]
		];
		$data = $_POST;
		if ($this->validate($data, $rules)) {
			$model = new Schedules();
			$model->createLesson($data);
		}
	}

	public function lesson_done(int $id): void
	{
		$model = new Schedules();
		$model->update([
			'id' => $id,
			'is_done' => '1'
		]);
		$model = null;

		header('Location: /schedules');
		exit;
	}

	public function lesson_delete(int $id): void
	{
		$model = new Schedules();
		$model->delete([
			'id' => $id
		]);
		$model = null;

		header('Location: /schedules');
		exit;
	}
}
