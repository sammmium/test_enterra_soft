<?php

return [

	'/' => [
		'controller' => 'HomeController',
		'action' => 'index',
		'view' => 'home/index.twig',
		'selected_menu_item' => 'user'
	],

	'/show/new/{id}' => [
		'controller' => 'HomeController',
		'action' => 'show',
		'view' => 'home/show.twig',
		'selected_menu_item' => 'user'
	],



	'/admin' => [
		'controller' => 'DashboardController',
		'action' => 'index',
		'view' => 'dashboard/index.twig',
		'selected_menu_item' => 'admin'
	],

];
