<?php

return [
	'app_name' => 'Enterra News',
    'owner' => 'Евгений Самойлов',
    'developer' => 'sammmium.dev@gmail.com',

	'sections' => [
		['alias' => 'admin', 'name' => 'Администратор', 'path' => '/admin', 'enabled' => true],
		['alias' => 'user', 'name' => 'Пользователь', 'path' => '/', 'enabled' => true],
	],

	'db' => [
		'name' => 'enterra',
		'host' => 'mysql-5.7',
		'user' => 'root',
		'password' => 'secret'
	]
];
