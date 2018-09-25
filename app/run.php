<?php

/**
 * Запуск приложения в обычном режиме
 * Приложение открыто через ./index.php
 */

if($_SERVER['REQUEST_METHOD'] !== 'GET' || !defined('__MODE_MAIN__')) {
	exit;
}

use App\{App,Debug,DataBase,CSRF,Explorer,Action,Tower};

require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

// Загрузка конфигурации приложения
App::$config = Explorer::configure();

// Указание временной зоны
date_default_timezone_set(App::$config['timezone']);

// Указание кодировки приложения
header('Content-type: text/html; charset=utf-8');

// Запуск сессии
session_set_cookie_params(App::$config['session_time']);
session_start();

// Загрузка шаблонизатора
App::$template = new Twig_Environment(
	new Twig_Loader_Filesystem(Explorer::path('views')), [
		'debug'	=> App::$config['developed'],
		'cache'	=> Explorer::path('views_cache'),
		'auto_reload' => true
	]
);

// Регистрация отладчика
Debug::handle();
Debug::integrateToTemplate();

// Загрузка модуля для работы с базой данных
App::$DB = new DataBase;
App::$DB->connect(App::$config['database']);

// Загрузка модуля защиты от межсайтовой подделки запросов
CSRF::start();
CSRF::integrateToTemplate();

// Загрузка модуля для работы с ajax и html-формами
Action::integrateToTemplate();

// Загрузка и запуск башен
Tower::boot();