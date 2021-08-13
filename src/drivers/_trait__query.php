<?php
namespace RusaDrako\driver_db\drivers;



trait _trait__query {



	/** Возвращает результат запроса в БД.
	* @param string $query Строка запроса.
	* @param bool $return_error Маркер возврата сообщения об ошибке.
 	* @return array Ответ БД. */
	public function query(string $query) {
		$result = $this->_query($query);
		# Возвращаем значение
		return $result;
	}



/**/
}
