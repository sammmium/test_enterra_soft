<?php

namespace src\models;

require_once 'Base.php';

class Revenues extends Base
{
	private string $table = 'revenues';

	protected array $availableColumns = [
		'subject_id',
		'value',
		'currency_id'
	];

	public function addRevenue(int $subjectId, int $currency_id, int $value): void
	{
		$columns = [
			'subject_id',
			'value',
			'currency_id'
		];
		$values = [
			$subjectId,
			$value,
			$currency_id
		];
		$query = "insert into " . $this->table . "(" . implode(', ', $columns) . ") values('" . implode("', '", $values) . "');";
		$this->set($query);
	}

	public function getTotalRevenue($year = null): array
	{
		$query[] = "select 
				round((sum(rv.value) / 100), 2) as total_revenue, 
				cr.currency as currency
			from " . $this->table . " as rv 
			left join currencies as cr on rv.currency_id = cr.id
			inner join subjects as sb on rv.subject_id = sb.id";
		$query[] = !is_null($year) ? " where YEAR(sb.subject_date) = '" . $year . "'" : '';
		$query[] = " group by currency_id;";
		return $this->get(implode(' ', $query));
	}        
}
