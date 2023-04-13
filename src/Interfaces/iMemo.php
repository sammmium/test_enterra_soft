<?php

namespace src\interfaces;

interface iMemo
{
	/**
	 * @param string $key
	 * @param array $value
	 * @return void
	 */
	public function setMemo(string $key, array $value): void;

	/**
	 * @param string $key
	 * @return array
	 */
	public function getMemo(string $key): array;

	/**
	 * @param string $key
	 * @return void
	 */
	public function resetMemo(string $key): void;
}
