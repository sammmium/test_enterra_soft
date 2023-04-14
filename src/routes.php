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

	'/admin/news/create' => [
		'controller' => 'DashboardController',
		'action' => 'create',
		'view' => 'dashboard/create.twig',
		'selected_menu_item' => 'admin'
	],

	'/admin/news/save' => [
		'controller' => 'DashboardController',
		'action' => 'save',
		'view' => 'dashboard/edit.twig',
		'selected_menu_item' => 'admin'
	],

	'/admin/edit/news/{id}' => [
		'controller' => 'DashboardController',
		'action' => 'edit',
		'view' => 'dashboard/edit.twig',
		'selected_menu_item' => 'admin'
	],

	'/admin/show/news/{id}' => [
		'controller' => 'DashboardController',
		'action' => 'show',
		'view' => 'dashboard/show.twig',
		'selected_menu_item' => 'admin'
	],

	'/admin/news/update' => [
		'controller' => 'DashboardController',
		'action' => 'update',
		'view' => 'dashboard/update.twig',
		'selected_menu_item' => 'admin'
	],

	'/admin/delete/news/{id}' => [
		'controller' => 'DashboardController',
		'action' => 'delete',
		'view' => 'dashboard/index.twig',
		'selected_menu_item' => 'admin'
	],

];
