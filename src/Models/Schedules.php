<?php

namespace src\models;

require_once 'Base.php';
include __DIR__ .'/../models/Contacts.php';
include __DIR__ .'/../models/Balances.php';
include __DIR__ .'/../models/Revenues.php';

class Schedules extends Base
{
	private string $table = 'subjects';

	protected array $availableColumns = [
		'contact_id',
		'subject_date',
		'subject_time',
		'is_done',
		'work_description',
		'subject_note'
	];

	public function getData(array $filter): array
	{
		$result = [];
		$result['events'] = [];

		// подготовка списка занятий на выбранную дату
		$query = "select
				sc.id as schedule_id,
				sc.contact_id as contact_id,
				cn.pupil as pupil,
				cn.parent_name as parent_name,
				sc.subject_date as subject_date,
				sc.subject_time as subject_time,
				sc.is_done as is_done,
				sc.work_description as work_description,
				sc.subject_note as subject_note
			from " . $this->table . " as sc
			left join contacts as cn on sc.contact_id = cn.id
			where " . $this->getPreparedConditions($filter) . " 
			order by sc.subject_time asc;";

		$events = $this->get($query);

		$result['contacts'] = [];

		if (count($events)) {
			$result['events'] = $events;

			$contactIdList = [];
			foreach ($events as $event) {
				$contactIdList[] = $event['contact_id'];
			}

			if (count($contactIdList)) {
				// подготовка списка клиентов, занятия с которыми запланированы на выбранную дату
				$contactModel = new Contacts();
				$result['contacts'] = $contactModel->getContactList(['contact_id' => $contactIdList]);
			}
		}

		return $result;
	}

	public function createLesson(array $data): int
	{
		$columns = [];
		$values = [];
		foreach ($data as $key => $value) {
			if ($this->isAvailableColumn($key)) {
				$columns[] = $key;
				$values[] = ($key == 'subject_date') ? $this->getTransformedDate($value) : $value;
			}
		}
		$columns[] = 'is_done';
		$values[] = 0;
		$query = "insert into " . $this->table . "(" . implode(', ', $columns) . ")  values('" . implode("', '", $values) . "');";
		return $this->set($query);
	}

	public function update(array $data): void
	{
		$values = [];
		$id = $data['id'];
		unset($data['id']);
		foreach ($data as $key => $value) {
			if ($this->isAvailableColumn($key)) {
				$values[] = "$key = '" . $value . "'";
			}
		}
		$query = "update " . $this->table . " set " . implode(', ', $values) . " where id = '$id';";
		$this->upd($query);

		$this->declineBalance($id);
	}

	public function delete(array $data): void
	{
		$query = "delete from " . $this->table . " where id = '" . $data['id'] . "';";
		$this->del($query);
	}

	private function declineBalance(int $id): void
	{
		$query = "select contact_id from " . $this->table . " where id = '" . $id . "';";
		$schedule_result = $this->get($query);
		$contact_id = $schedule_result[0]['contact_id'];

		$model_contacts = new Contacts();
		$contact_result = $model_contacts->getRate($contact_id);
		$rate = $contact_result[0]['rate'];

		$model_balances = new Balances();
		$balance_result = $model_balances->getCurrentValue($contact_id);
		$balance_id = $balance_result['id'];
		$current_value = $balance_result['current_value'];
		$currency_id = $balance_result['currency_id'];

		$model_revenues = new Revenues();
		$model_revenues->addRevenue($id, $currency_id, $rate);


		if ($rate < $current_value) {
			$declined_value = $current_value - $rate;

			$model_balances = new Balances();
			$model_balances->updateBalanceValue([
				'id' => $balance_id,
				'current_value' => $declined_value
			]);
		}
	}

	public function getDashboardData(): array
	{
		$result = [];

		$query = "select count(id) as planned_lessons from " . $this->table . " where is_done = '0';";
		$result['lessons']['planned_lessons'] = $this->get($query)[0]['planned_lessons'];

		$query = "select count(id) as finished_lessons from " . $this->table . " where is_done = '1';";
		$result['lessons']['finished_lessons'] = $this->get($query)[0]['finished_lessons'];

		return $result;
	}

	public function getSubjectList(array $filter = []): array
	{
		$result = [];
		
		if (count($filter) && $filter['selected_contact'] == 'all') {
			return $result;
		}

		$query = "select
				id as subject_id,
				subject_date,
				work_description,
				subject_note
			from subjects as sb
			where 
				year(subject_date) = '" . $filter['selected_period'] . "' 
				and contact_id = '" . $filter['selected_contact'] . "';";

		$subjects = $this->get($query);

		$result['subjects'] = $subjects;

		$result['subject_count'] = $this->getSubjectCount($subjects);

		return  $result;
	}

	public function getSubjectCount(array $subjects = []): int
	{
		return count($subjects);
	}

	public function getSubjectPeriodRevenue(array $subjects = []): array
	{
		$result = [];

		$subjectIdList = [];
		foreach ($subjects as $subject) {
			$subjectIdList[] = $subject['subject_id'];
		}
		
		$model_revenues = new Revenues();


		return $result;
	}
}
