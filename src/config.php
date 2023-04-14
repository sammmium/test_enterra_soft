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
		//если БД развернута в контейнере
		'host' => 'mysql-5.7',
		//если БД развернута на локальной машине 
		//'host' => '127.0.0.1',
		'user' => 'root',//заменить на свои данные
		'password' => 'secret'//заменить на свои данные
	]
];
