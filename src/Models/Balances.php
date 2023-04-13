<?php

namespace src\models;

require_once 'Base.php';

class Balances extends Base
{
	private string $table = 'balances';

	protected array $availableColumns = [
		'current_value',
		'currency_id',
		'contact_id'
	];

	/**
	 * @param array $data
	 * @return int
	 */
	public function store(array $data): int
	{
		$columns = [];
		$values = [];
		foreach ($data as $key => $value) {
			if ($this->isAvailableColumn($key)) {
				$columns[] = $key;
				$values[] = ($key === 'current_value') ? ($value * 100) : $value;
			}
		}
		$query = "insert into " . $this->table . "(" . implode(', ', $columns) . ")  values('" . implode("', '", $values) . "');";
		return $this->set($query);
	}

	/**
	 * @param array $data
	 * @return void
	 */
	public function update(array $data): void
	{
		$values = [];
		$contact_id = $data['contact_id'];
		unset($data['contact_id']);
		foreach ($data as $key => $value) {
			if ($this->isAvailableColumn($key)) {
				$values[] = ($key === 'current_value')
					? "$key = '" . ($value * 100) . "'"
					: "$key = '" . $value . "'";
			}
		}
		$query = "update " . $this->table . " set " . implode(', ', $values) . " where contact_id = '$contact_id';";
		$this->upd($query);
	}

	public function getCurrentValue(int $contactId): array
	{
		$query = "select id, current_value, currency_id from " . $this->table . " where contact_id = '" . $contactId . "';";
		return $this->get($query)[0];
	}

	public function updateBalanceValue(array $data): void
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
	}

	public function getDashboardData(): array
	{
		$result = [];

		$query = "select 
				round(sum(bl.current_value / 100), 2) as current_value, 
				cr.currency as currency 
			from balances as bl 
			inner join currencies as cr on bl.currency_id = cr.id 
			group by bl.currency_id;";
		$result['balances'] = $this->get($query);

		return $result;
	}
}
