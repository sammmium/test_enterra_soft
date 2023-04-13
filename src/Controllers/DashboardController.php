<?php

namespace src\Controllers;

use src\models\Balances;
use src\models\Contacts;
use src\models\Schedules;

require_once 'MainController.php';

//require_once __DIR__ .'/../models/Contacts.php';
require_once __DIR__ .'/../models/Schedules.php';
require_once __DIR__ .'/../models/Balances.php';
require_once __DIR__ .'/../models/Currencies.php';

class DashboardController extends MainController
{
	protected array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function index()
	{
		$content = [];

		/*
		 * На дашборд нужно вывести аналитическую информацию в виде таблицы (ключ - значение)
		 *
		 * Количество клиентов:
		 * 		активных: 10
		 * 		архивных: 141
		 * На балансе:
		 * 		20000 BYN
		 * 		15000 USD
		 * 		80000 RUB
		 * Проведено занятий: 180
		 * Запланировано занятий: 2640
		 */
		$contacts_model = new Contacts();
		$content += $contacts_model->getDashboardData();

		$balances_model = new Balances();
		$content += $balances_model->getDashboardData();

		$schedules_model = new Schedules();
		$content += $schedules_model->getDashboardData();

//		var_dump($content);exit;

		return $this->getContent($content);
	}
}
