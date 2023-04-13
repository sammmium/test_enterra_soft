<?php

namespace src\Controllers;

use src\models\Contacts;
use src\models\Revenues;

require_once 'MainController.php';
require __DIR__ .'/../models/Revenues.php';
require __DIR__ .'/../models/Contacts.php';

class ReportsController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/*
	 * при первой загрузке страницы на ней будет отображена общая
	 * сумма заработанных денег
	 *
	 * для фильтра готовим массивы: периодов (годов) и контактов
	 *
	 * при выборе года и/или контакта в выпадающих списках (в любом из них)
	 * должно сработать событие, которое заполнит выбранным значением скрытое
	 * поле и отобразит скрытую кнопку для отправки формы по адресу /reports/filter
	 * 
	 */

	public function index(): array
	{
		$content = [];
		$content['periods'] = $this->getPeriodList();

		$model_contacts = new Contacts();
		$content['contacts'] = [];
		$content['contacts'][] = ['key' => 0, 'value' => 'Выберите клиента'];
		foreach ($model_contacts->getContactList() as $contact) {
			$value = $contact['pupil'] . ' (' . $contact['parent_name'] . ')';
			$content['contacts'][] = ['key' => $contact['contact_id'], 'value' => $value];
		}

		$filter = [];

		if (!empty($_POST)) {
			$filter = $_POST;
		}

		$thisYear = date('Y');
		$content['this_year'] = $thisYear;
		$model_revenues = new Revenues();
		$content['totals'] = $model_revenues->getTotalRevenue($thisYear);


		



		//var_dump($content);exit;
		return $this->getContent($content);
	}

	/*
	 * после применения фильтра:
	 *
	 * на странице дорисуется вторая и третья части страницы (после фильтра)
	 *
	 * вторая часть: сравнение выбранного года с прошлым (общий доход с разбивкой по валютам)
	 *
	 * третья часть: полный отчет за выбранный период в отношении выбранного контакта.
	 * если период не выбран, но выбран контакт, то отображаем информацию за текущий год.
	 * если контакт не выбран, то содержимое третьей части остается пустым.
	 */
	public function filter(): array
	{
		var_dump($_POST);exit;
		return [];
	}

	private function getPeriodList(): array
	{
		$thisYear = date('Y');
		$period = 5;
		$yearList = [];
		$yearList[] = ['key' => 0, 'value' => 'Выберите год'];
		$yearList[] = ['key' => $thisYear, 'value' => $thisYear];
		for ($i = 1; $i < $period; $i++) {
			$yearList[] = ['key' => ($thisYear - $i), 'value' => ($thisYear - $i)];
		}
		return $yearList;
	}
}
