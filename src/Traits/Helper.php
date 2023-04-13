<?php

namespace src\Traits;

trait Helper
{
    protected static function transformDate(string $date, string $toLocale = 'en'): string
	{
		if (strpos($date, '.') !== false) {
			if ($toLocale == 'en') {
				list($d, $m, $y) = explode('.', $date);
				
				return $y . '-' . $m . '-' . $d;
			}

			return $date;
		}

		if ($toLocale == 'ru') {
			list($y, $m, $d) = explode('-', $date);

			return $d . '.' . $m . '.' . $y;
		}

		return $date;
	}
}
