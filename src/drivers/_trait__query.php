<?php
namespace RusaDrako\driver_db\drivers;



trait _trait__query {



	/** Функция возвращает возвращает результат запроса в БД.
	* @param string $query Строка запроса.
	* @param bool $return_error Маркер возврата сообщения об ошибке.
 	* @return array Ответ БД. */
	public function query($query, $return_error = false) {
		$result = $this->_query($query, $return_error);
		# Возвращаем значение
		return $result;
	}



/**/
}
