<?php

namespace src\models;

require_once 'Base.php';

class Currencies extends Base
{
	private string $table = 'currencies';

	private function getRawCurrencies(): array
	{
		$query = "select 
				cr.id as currency_id,
				cr.currency as currency
			from " . $this->table . " as cr";
		return $this->get($query);
	}

	public function getCurrencies(): array
	{
		return $this->getRawCurrencies();
	}
}
