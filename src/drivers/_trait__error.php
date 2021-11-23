<?php
namespace RusaDrako\driver_db\drivers;



trait _trait__error {



	/** Генерация ошибки.
	 * @param string $num_error Номер ошибки.
	 * @param string $error Текст ошибки.
	 * @param string $query Тело запроса.
	 */
	public function _error(string $num_error, string $error, string $query = '') {
		# Вывод сообщения об ошибке
//		$text_error = "{$num_error}: {$error}<br>\r\n{$query}";
		# Генерация ошибки
		throw new \RusaDrako\driver_db\db_exception('Ошибка запроса в БД (' . \get_class() . "): {$num_error}: {$error}<br>\r\n{$query}", $num_error);
	}



/**/
}
