<?php

namespace Tower;
use App\App;

class Tuner {

	public function __construct(bool $post) {

		// Добавление в локальное хранилище пользовательских настроек
		App::$settings = (new \Thing\Settings)->parse();

		if(!$post) {
			// Добавление глобальных переменных в шаблонизатор
			App::$template->addGlobal('_settings', App::$settings);
		}

	}

}