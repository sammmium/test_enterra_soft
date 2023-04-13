<?php

namespace src\models;

use src\Traits\Helper;

require_once __DIR__ . '/../Traits/Helper.php';

class Base
{
	protected $mysqli = null;

	protected array $availableColumns = [];

	use Helper;

	public function connect(): void
	{
		$config = require __DIR__ . '/../config.php';
		$this->mysqli = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['name']);
		$this->execute("SET NAMES utf8");
	}

	public function disconnect(): void
	{
		$this->mysqli = null;
	}

	protected function get(string $query): array
	{
		$this->connect();
		$result = [];
		$queryResult = $this->execute($query);
		while ($row = mysqli_fetch_assoc($queryResult)) {
			$result[] = $row;
		}
		$this->disconnect();

		return $result[0];
	}

	protected function all(string $query): array
	{
		$this->connect();
		$result = [];
		$queryResult = $this->execute($query);
		while ($row = mysqli_fetch_assoc($queryResult)) {
			$result[] = $row;
		}
		$this->disconnect();
		
		return $result;
	}

	protected function set(string $query): int
	{
		$this->connect();
		$this->execute($query);
		$last_insert_id = mysqli_insert_id($this->mysqli);
		$this->disconnect();

		return $last_insert_id;
	}

	protected function update(string $query): void
	{
		$this->connect();
		$this->execute($query);
		$this->disconnect();
	}

	protected function delete(string $query): void
	{
		$this->connect();
		$this->execute($query);
		$this->disconnect();
	}

	protected function isAvailableColumn(string $column): bool
	{
		foreach ($this->availableColumns as $item) {
			if ($item === $column) {
				return true;
			}
		}

		return false;
	}

	protected function execute(string $query)
	{
		return mysqli_query($this->mysqli, $query);
	}

	protected function getPreparedConditions(array $filter = []): string
	{
		$result = '';
		$conditions = [];

		if (count($filter)) {
			foreach ($filter as $key => $value) {
				if (!is_null($value)) {
					$value = (strpos($key, 'date') !== false) ? self::transformDate($value) : $value;

					if (is_array($value)) {
						$conditions[] = $key . " in ('" . implode("', '", $value) ."')";
					} else {
						$conditions[] = $key . " = '" . $value ."'";
					}
				}
			}
		}

		if (count($conditions)) {
			$result = implode(' and ', $conditions);
		}

		return $result;
	}
}


