<?php

namespace src\models;

require_once 'Base.php';

class Errors extends Base
{
	private string $table = 'errors';

	protected array $availableColumns = [
		'error_key',
		'error_value'
	];

	public function setError(string $key, array $value): void
	{
		$hashedKey = md5($key);
		$value = json_encode($value);
		if (!$this->hasErrorKey($hashedKey)) {
			$query = "insert into " . $this->table . " (error_key, error_value) values('$hashedKey', '$value');";
			$this->set($query);
		} else {
			$query = "update " . $this->table . " set error_value='$value' where error_key = '$hashedKey';";
			$this->upd($query);
		}
	}

	private function hasErrorKey(string $hashedKey): bool
	{
		$query = "select count(error_key) as cnt from " . $this->table . " where error_key = '$hashedKey';";
		return $this->get($query)[0]['cnt'] == 1;
	}

	public function getError(string $key): array
	{
		$hashedKey = md5($key);
		if ($this->hasErrorKey($hashedKey)) {
			$query = "select error_value from errors where error_key = '$hashedKey';";
			$result = json_decode($this->get($query)[0]['error_value'], true);
			$this->resetError($key);
			return $result;
		}
		return [];
	}

	public function resetError(string $key): void
	{
		$hashedKey = md5($key);
		$query = "delete from " . $this->table . " where error_key = '$hashedKey';";
		$this->del($query);
	}
}
