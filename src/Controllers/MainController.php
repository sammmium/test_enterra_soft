<?php

namespace src\Controllers;

class MainController
{
	protected array $errors;

	private array $types = [
		'numeric' => 'is_numeric',
		'text' => 'is_string'
	];

	/**
	 * @return array
	 */
	protected function getMenu(): array
	{
		$menu = [];

		foreach ($this->config['sections'] as $section) {
			if ($section['enabled']) {
				$menu[] = $section;
			}
		}

		return $menu;
	}

	/**
	 * @return array
	 */
	protected function getFooter(): array
	{
		return [
			'owner' => $this->config['owner'],
			'developer' => $this->config['developer']
		];
	}

	protected function getContent(array $specialContent = []): array
	{
		$content = count($specialContent) ? $specialContent : [];
		$content['app_name'] = $this->config['app_name'];
		$content['menus'] = $this->getMenu();
		$content['footer'] = $this->getFooter();
		return $content;
	}

	protected function validate($data, $rules): bool
	{
		$this->errors = [];

		foreach ($data as $key => $value) {
			$this->validateItem($key, $value, $rules[$key]);
		}

		if (count($this->errors)) {
			return false;
		}

		return true;
	}

	private function validateItem($key, $value, $rule): void
	{
		$method = $this->types[$rule['type']];
		if ($rule['required']) {
			if (empty($value)) {
				$this->errors[$key][] = 'Поле не может быть пустым.';
			}
		}
		if (!empty($value)) {
			if (!$method($value)) {
				$this->errors[$key][] = 'Неверный тип данных.';
			}
		}
	}
}
