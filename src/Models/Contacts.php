<?php

namespace src\models;

use src\Models\FileLogger;

require_once 'Base.php';
require_once 'FileLogger.php';

class Contacts extends Base
{
	private string $table = 'contacts';

	protected array $availableColumns = [
		'parent_name',
		'parent_phone',
		'pupil',
		'description',
		'rate',
		'create_at',
		'update_at',
		'restore_at',
		'is_archive'
	];

	public function getContactList(array $filter = []): array
	{
		return $this->getRawList(0, $filter);
	}

	private function getRawList(int $isArchived = 0, array $filter = []): array
	{
		$filter['is_archive'] = $isArchived;
		$query = "select 
    			cn.id as contact_id, 
    			cn.parent_name as parent_name,
    			cn.parent_phone as parent_phone,
    			cn.pupil as pupil,
    			cn.description as description,
    			round((cn.rate / 100), 2) as rate,
    			cn.create_at as create_at,
    			cn.update_at as update_at,
    			cn.restore_at as restore_at,
    			cn.is_archive as is_archive,
    			bl.id as balance_id,
    			round((bl.current_value / 100), 2) as current_value,
    			cr.id as currency_id,
    			cr.currency as currency
			from " . $this->table . " cn
         	left join balances bl on cn.id = bl.contact_id
			left join currencies cr on bl.currency_id = cr.id
         	where " . $this->getPreparedConditions($filter);
		return $this->get($query);
	}

	public function getArchivedContactList(): array
	{
		return $this->getRawList(1);
	}

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
				$values[] = ($key === 'rate') ? ($value * 100) : $value;
			}
		}
		$columns[] = 'create_at';
		$values[] = date('Y-m-d');
		$columns[] = 'is_archive';
		$values[] = 0;
		$query = "insert into " . $this->table . "(" . implode(', ', $columns) . ")  values('" . implode("', '", $values) . "');";
		return $this->set($query);
	}

	private function getRawContact(int $id): array
	{
		$query = "select 
    			cn.id as contact_id, 
    			cn.parent_name as parent_name,
    			cn.parent_phone as parent_phone,
    			cn.pupil as pupil,
    			cn.description as description,
    			round((cn.rate / 100), 2) as rate,
    			cn.create_at as create_at,
    			cn.update_at as update_at,
    			cn.restore_at as restore_at,
    			cn.is_archive as is_archive,
    			bl.id as balance_id,
    			round((bl.current_value / 100), 2) as current_value,
    			cr.id as currency_id,
    			cr.currency as currency
			from " . $this->table . " cn
         	left join balances bl on cn.id = bl.contact_id
			left join currencies cr on bl.currency_id = cr.id
         	where cn.id = '$id';";
		return $this->get($query);
	}

	public function getContact(int $id): array
	{
		return $this->getRawContact($id)[0];
	}

	/**
	 * UPDATE table_name
	 * SET column1 = value1, column2 = value2, ...
	 * WHERE condition;
	 *
	 * @param array $data
	 * @return void
	 */
	public function update(array $data): void
	{
		$values = [];
		foreach ($data as $key => $value) {
			if ($this->isAvailableColumn($key)) {
				$values[] = ($key === 'rate')
					? "$key = '" . ($value * 100) . "'"
					: "$key = '" . $value . "'";
			}
		}
		$contact_id = $data['contact_id'];
		$values[] = "update_at = '" . date('Y-m-d') . "'";
		$query = "update " . $this->table . " set " . implode(', ', $values) . " where id = '$contact_id';";
		$this->upd($query);

		$logger = new FileLogger();
		$logger->info("Контакт #$contact_id обновлен");
	}

	public function getRate(int $contactId): array
	{
		$query = "select rate from " . $this->table . " where id = '" . $contactId . "';";
		return $this->get($query);
	}

	public function getDashboardData(): array
	{
		$result = [];

		$query = "select count(id) as active_clients from " . $this->table . " where is_archive = '0';";
		$result += $this->get($query)[0];

		$query = "select count(id) as archive_clients from " . $this->table . " where is_archive = '1';";
		$result += $this->get($query)[0];

		return $result;
	}
}
