<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД SQLite (sqlsrv).
 * @created 2020-12-15
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class driver_sqlite3 extends _abs_driver {

	use _trt__get_val;
	use _trt__update;
	use _trt__insert_val;
	use _trt__delete;
	use _trt__query;
	use _trt__error;

	/** Установка настроек */
	protected function _set_setting($settings) {
		# Настройки подключения к БД
		$this->_db_name = $settings['db'] ?: null; # Имя БД
	}


	/** Функция создания соединения с БД */
	protected function _db_connect() {
		# Подключаемся к БД
		$db = new \SQLite3($this->_db_name);
		# Присваеваем переменной соединение с БД
		$this->db = $db;
		# Возвращаем результат
		return true;
	}

	/** Функция разрыва соединения с БД */
	protected function _db_disconnect() {
		# Если БД подключена
		if ($this->db) {
			# Отключаем БД
			$this->db->close();
		}
		parent::_db_disconnect();
	}

	/** Функция возвращает возвращает результат запроса в БД.
	* @param string $query Строка запроса.
	* @param bool $return_error Маркер возврата сообщения об ошибке.
 	* @return array Ответ БД.
	*/
	protected function _query($query) {
		# Значение результата по-умолчанию
		$result = false;
		# Если переменная запроса не пустая
		if (!empty($query)) {
			# Если переменная запроса является строкой
			if (\is_string($query)) {
				# Выполнение запроса
				$result = $this->db->query($query);
			}
		}
		# Возвращаем значение
		return $result;
	}

	/** Функция возвращает массив результата запроса select (массив полей ID) или false.
	* @param string $query Строка запроса.
	* @param string $assoc Ассоциативный массив.
	* @return array Ответ БД (массив данных).
	*/
	public function select(string $query, bool $assoc = true) {
		# Значение результата по-умолчанию
		$arr_result = [];
		# Выполняем запрос
		if ($result = $this->_query($query)) {
			$arr_result = [];
			# Если следует использовать ключевое поле
			if ($assoc) {
				$assoc_mark = SQLITE3_ASSOC;
			} else {
				$assoc_mark = SQLITE3_NUM;
			}
			# Проходим по результату запроса
			while ($array_temp = $result->fetchArray($assoc_mark)) {
				# Формируем массив результатов - обезличенный
				$arr_result[] = $array_temp;
			}
		}
		# Возвращаем значение
		return $arr_result;
	}

	/** Чистка переменной для БД.
	* @param array $v Значение переменной.
	*/
	protected function _db_get_set_clean($v) {
		$v = \htmlspecialchars(\stripslashes(\trim($v)));
		$v = \str_replace("'", '&#039;', $v);
		return $v;
	}

	/** Возвращает ID последней вставленной строки или значение последовательности */
	public function insert_id() {
		return $this->_count_rows;
	}

/**/
}
