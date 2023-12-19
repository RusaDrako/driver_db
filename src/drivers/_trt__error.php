<?php

namespace RusaDrako\driver_db\drivers;

use RusaDrako\driver_db\ExceptionDB;

trait _trt__error{

	/**
	 * Генерация ошибки.
	 * @param string $num_error Номер ошибки.
	 * @param string $error Текст ошибки.
	 * @param string $query Тело запроса.
	 */
	public function _error(string $num_error, string $error, string $query=''){
		# Генерация ошибки
		$message = 'Ошибка запроса в БД ('.\get_class()."): {$num_error}: {$error}<br>\r\n{$query}";
		throw new DriverDB($message, (int)$num_error);
	}

/**/
}
