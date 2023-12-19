<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД MySQL (mysql).
 * @created 2020-12-15
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class driver_mysql extends _abs_driver {

	/** Кодировка */
	protected $_db_encoding;

	/** Установка настроек */
	protected function _set_setting($settings) {
		parent::_set_setting($settings);
		$this->_db_host = $this->_db_host . ($this->_db_port ? ":{$this->_db_port}" : '');
		# Настройки подключения к БД
		$this->_db_encoding = $settings['encoding'];   # Кодировка
	}

	/** Функция создания соединения с БД */
	protected function _db_connect() {
		# Подключаемся к БД-серверу
		$db = @\mysql_connect($this->_db_host, $this->_db_user, $this->_db_password);
		if (!$db) {
			# Выводим сообщение об ошибке
			throw new \Exception(__FILE__ . '(' . __LINE__ . ') Ошибка подключения к БД: ' . \mysql_connect_error());
		} else {
			# Присваеваем переменной соединение с БД
			$this->db = $db;
			# Настройка кодировка
			\mysql_set_charset($this->_db_encoding, $this->db);
			# Подключение заданной БД
			\mysql_select_db($this->_db_name, $this->db);
			# Возвращаем результат
			return true;
		}
	}

	/** Функция разрыва соединения с БД */
	protected function _db_disconnect() {
		# Если БД подключена
		if ($this->db) {
			# Отключаем БД
			\mysql_close($this->db);
		}
		parent::_db_disconnect();
	}

	/** Функция возвращает возвращает результат запроса в БД.
	* @param string $query Строка запроса.
 	* @return array Ответ БД. */
	protected function _query(string $query) {
		# Значение результата по-умолчанию
		$result = false;
		# Если переменная запроса не пустая
		if (!empty($query)) {
			# Если переменная запроса является строкой
			if (is_string($query)) {
				# Выполнение запроса
				$result = \mysql_query($query, $this->db);
$this->_count_rows = \mysql_affected_rows($this->db);
//echo 'Затронуто строк: ' . $this->_count_rows . '<br>';
				# Получение ошибки запроса
				$num_error = \mysql_errno($this->db);
				# Если есть ошибка запроса
				if ($num_error) {
					# Ошибка при удалении ключевого элементов, где нет каскадного удаления
					if ('delete ' == substr(mb_strtolower($query), 0, 7)) {
						# Возвращаем false
						return false;
					}
					$error = \mysql_error($this->db);
					# Вывод/генерация сообщения об ошибке
					$this->_error($num_error, $error, $query);
				} else {
					# Если вызывался call, то нужна чистка, иначе не срабатывает
					# Для остальных выдаёт ошибку
					if ('call' == strtolower(substr(trim($query), 0, 4))) {
						\mysql_next_result($this->db);
					}
				}
			}
		}
		# Возвращаем значение
		return $result;
	}

	/** Функция возвращает массив результата запроса select (массив полей ID) или false.
	 * @param string $query Строка запроса.
	 * @param string $assoc Ассоциативный массив.
	 * @return array Ответ БД (массив данных). */
	public function select(string $query, bool $assoc = true) {
		# Значение результата по-умолчанию
		$arr_result = array();
		# Выполняем запрос
		if ($result = $this->_query($query)) {
			$arr_result = array();
			# Если следует использовать ключевое поле
			if ($assoc) {
				# Проходим по результату запроса
				while ($array_temp = \mysql_fetch_assoc($result)) {
					# Формируем массив результатов - с использованием ключа
					$arr_result[] = $array_temp;
				}
			} else {
				# Проходим по результату запроса
				while ($array_temp = \mysql_fetch_row($result)) {
					# Формируем массив результатов - обезличенный
					$arr_result[] = $array_temp;
				}
			}
		}
		# Возвращаем значение
		return $arr_result;
	}

	/** Чистка переменной для БД.
	 * @param array $v Значение переменной.
	 */
	protected function _db_get_set_clean($v) {
		$v = \mysql_real_escape_string($v, $this->db);
		$v = \str_replace("'", '&#039;', $v);
		return $v;
	}

	/**  Возвращает ID последней вставленной строки или значение последовательности */
	public function insert_id() {
		return \mysql_insert_id($this->db);
	}

/**/
}
