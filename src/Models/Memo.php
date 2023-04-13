<?php

namespace src\models;

use src\interfaces\iMemo;

require_once 'Base.php';
require __DIR__ . '/../interfaces/iMemo.php';

class Memo extends Base implements iMemo
{
	private string $table = 'memo';

	protected array $availableColumns = [
		'memo_key',
		'memo_value'
	];

	/**
	 * @param string $key
	 * @param array $value
	 * @return void
	 */
	public function setMemo(string $key, array $value): void
	{
		$hashedKey = md5($key);
		$value = json_encode($value);
		if (!$this->hasMemoKey($hashedKey)) {
			$query = "insert into " . $this->table . " (memo_key, memo_value) values('$hashedKey', '$value');";
			$this->set($query);
		} else {
			$query = "update " . $this->table . " set memo_value='$value' where memo_key = '$hashedKey';";
			$this->upd($query);
		}
	}

	/**
	 * @param string $hashedKey
	 * @return bool
	 */
	private function hasMemoKey(string $hashedKey): bool
	{
		$query = "select count(memo_key) as cnt from " . $this->table . " where memo_key = '$hashedKey';";
		return $this->get($query)[0]['cnt'] == 1;
	}

	/**
	 * @param string $key
	 * @return array
	 */
	public function getMemo(string $key): array
	{
		$hashedKey = md5($key);
		if ($this->hasMemoKey($hashedKey)) {
			$query = "select memo_value from " . $this->table . " where memo_key = '$hashedKey';";
			$result = json_decode($this->get($query)[0]['memo_value'], true);
			$this->resetMemo($key);
			return $result;
		}
		return [];
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public function resetMemo(string $key): void
	{
		$hashedKey = md5($key);
		$query = "delete from " . $this->table . " where memo_key = '$hashedKey';";
		$this->del($query);
	}
}
