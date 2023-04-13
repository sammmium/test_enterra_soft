<?php

namespace src\Controllers;

use src\models\Contacts;
use src\models\Currencies;
use src\models\Balances;
use src\models\Errors;
use src\models\Memo;

require 'MainController.php';
include __DIR__ .'/../models/Contacts.php';
include __DIR__ .'/../models/Currencies.php';
include __DIR__ .'/../models/Balances.php';
include __DIR__ .'/../models/Errors.php';
include __DIR__ .'/../models/Memo.php';

class ContactsController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @return array
	 */
	public function index(): array
	{
		$content = [];
		$model = new Contacts();
		$content['contact_list'] = $model->getContactList();

		return $this->getContent($content);
	}

	/**
	 * @return array
	 */
	public function create(): array
	{
		$content = [];
		$model = new Currencies();
		$content['currencies'] = $model->getCurrencies();

		$memo = new Memo();
		$content['memo'] = $memo->getMemo('contact_create');

		$errors = new Errors();
		$content['errors'] = $errors->getError('contact_create');

		return $this->getContent($content);
	}

	/**
	 * @return void
	 */
	public function store(): void
	{
		$rules = [
			'parent_name' => ['type' => 'text', 'required' => true],
			'parent_phone' => ['type' => 'numeric', 'required' => true],
			'pupil' => ['type' => 'text', 'required' => true],
			'current_value' => ['type' => 'numeric', 'required' => false],
			'rate' => ['type' => 'numeric', 'required' => true],
			'currency_id' => ['type' => 'numeric', 'required' => true],
			'description' => ['type' => 'text', 'required' => false]
		];

		$data = $_POST;

//		var_dump($data);exit;

		if ($this->validate($data, $rules)) {
			$model = new Contacts();
			$contact_id = $model->store($data);
			$model = null;

			$dataBalance = $data;
			$dataBalance['contact_id'] = $contact_id;
			$model = new Balances();
			$balance_id = $model->store($dataBalance);
			$model = null;

			header('Location: /contacts');
			exit;
		}

		$errors = new Errors();
		$errors->setError('contact_create', $this->errors);

		$memo = new Memo();
		$memo->setMemo('contact_create', $data);

		header('Location: /contact/create');
		exit;
	}

	public function edit(int $id): array
	{
		$content = [];
		$model = new Contacts();
		$content['contact'] = $model->getContact($id);

		$model = new Currencies();
		$content['currencies'] = $model->getCurrencies();

		return $this->getContent($content);
	}

	public function update(int $id): void
	{
		$rules = [
			'parent_name' => ['type' => 'text', 'required' => true],
			'parent_phone' => ['type' => 'numeric', 'required' => true],
			'pupil' => ['type' => 'text', 'required' => true],
			'current_value' => ['type' => 'numeric', 'required' => false],
			'rate' => ['type' => 'numeric', 'required' => true],
			'currency_id' => ['type' => 'numeric', 'required' => true],
			'description' => ['type' => 'text', 'required' => false]
		];

		$data = $_POST;

		if ($this->validate($data, $rules)) {
			$model = new Contacts();
			$dataContact = $data;
			$dataContact['contact_id'] = $id;
			$model->update($dataContact);
			$model = null;

			$dataBalance = $data;
			$dataBalance['contact_id'] = $id;
			$model = new Balances();
			$model->update($dataBalance);
			$model = null;

			header('Location: /contacts');
			exit;
		}

		$errors = new Errors();
		$errors->setError('contact_update', $this->errors);

		$memo = new Memo();
		$memo->setMemo('contact_update', $data);

		header('Location: /contact/edit/' . $id);
		exit;
	}

	public function archive(int $id): void
	{
		$dataContact = [];
		$dataContact['contact_id'] = $id;
		$dataContact['is_archive'] = 1;

		$model = new Contacts();
		$model->update($dataContact);
		$model = null;

		header('Location: /contacts');
		exit;
	}

	public function contacts_archived(): array
	{
		$content = [];
		$model = new Contacts();
		$content['contact_list'] = $model->getArchivedContactList();

		return $this->getContent($content);
	}

	public function restore(int $id): void
	{
		$dataContact = [];
		$dataContact['contact_id'] = $id;
		$dataContact['is_archive'] = 0;

		$model = new Contacts();
		$model->update($dataContact);
		$model = null;

		header('Location: /contact/edit/' . $id);
		exit;
	}
}
