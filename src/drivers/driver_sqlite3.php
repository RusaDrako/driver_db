<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД SQLite (sqlsrv).
 * @created 2020-12-15
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class driver_sqlite3 extends _abs_driver {

	use _trt__get_val;
	use _trt__insert_val;

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

	/**
	 * Функция возвращает возвращает результат запроса в БД.
	 * @param string $query Строка запроса.
	 * @return array Ответ БД.
	 */
	protected function _query(string $query) {
		# Значение результата по-умолчанию
		$result = false;
		# Если переменная запроса не пустая
		if (!empty($query)) {
			# Если переменная запроса является строкой
			if (\is_string($query)) {
				# Выполнение запроса
//				try {
					$result = $this->db->query($query);
					if ($result === false) {
						# Выводим сообщение об ошибке
						$this->_error($this->db->lastErrorCode(), $this->db->lastErrorMsg(), $query);
					}
//				} catch (\Exception $e) {
//					var_dump($e);
//					exit;
//				}
			}
		}
		# Возвращаем значение
		return $result;
	}

	/**
	 * Функция возвращает массив результата запроса select (массив полей ID) или false.
	 * @param string $query Строка запроса.
	 * @param bool $assoc Возвращать ассоциотивный массив полей
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
