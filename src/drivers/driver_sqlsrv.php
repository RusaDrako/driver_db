<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД MySQL (sqlsrv).
 * @created 2020-12-15
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class driver_sqlsrv extends _abs_driver {

	use _trt__get_set;
	use _trt__update;
	use _trt__insert;
	use _trt__delete;
	use _trt__query;
	use _trt__error;

	/** Кодировка системы */
	protected $_db_encoding_sys;
	/** Кодировка БД */
	protected $_db_encoding_db;

	protected 	$_flag_start = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED; ";
	protected 	$_flag_finish = "";

	/** Установка настроек */
	protected function _set_setting($settings) {
		parent::_set_setting($settings);
		$this->_db_host = $this->_db_host . ($this->_db_port ? ":{$this->_db_port}" : '');
		# Настройки подключения к БД
		$this->_db_encoding_sys = $settings['encoding_sys'] ?: null;   # Кодировка Системы
		$this->_db_encoding_db = $settings['encoding_db'] ?: null;     # Кодировка БД
	}

	/** Функция создания соединения с БД */
	protected function _db_connect() {
		# Подключаемся к БД-серверу
		$connectionInfo = ["Database"=>$this->_db_name, "UID"=>$this->_db_user, "PWD"=>$this->_db_password];
		$db = \sqlsrv_connect($this->_db_host, $connectionInfo);
		if (false === $db) {
			$errors = \sqlsrv_errors();
			$num_error = $errors['code'];
			$error = $errors['message'];
			# Вывод сообщения об ошибке
			throw new \Exception(__FILE__ . '(' . __LINE__ . ') Ошибка подключения к БД: ' . $num_error . ': ' . $error);
		}
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
			\sqlsrv_close($this->db);
		}
		parent::_db_disconnect();
	}

	/** Функция возвращает возвращает результат запроса в БД.
	* @param string $query Строка запроса.
	* @param bool $return_error Маркер возврата сообщения об ошибке.
 	* @return array Ответ БД. */
	protected function _query($query) {
		# Значение результата по-умолчанию
		$result = false;
		# Если переменная запроса не пустая
		if (!empty($query)) {
			# Если переменная запроса является строкой
			if (\is_string($query)) {
				# Выполнение запроса
				$result = \sqlsrv_query($this->db, $this->_flag_start . $query . $this->_flag_finish);
				if ($result === false) {
					# Получение ошибки запроса
					if(($errors = \sqlsrv_errors()) != null) {
						# Получение ошибки запроса
						$num_error = $error['code'];
						# Сообщение об ошибке
						$error = $error['message'];
						# Вывод/генерация сообщения об ошибке
						$this->_error($num_error, $error, $query);
					}
				} else {
					$this->_count_rows = \sqlsrv_rows_affected($result);
				}
				# Если вызывался call, то нужна чистка, иначе не срабатывает
				# Для остальных выдаёт ошибку
				if ('call' == \strtolower(\substr(\trim($query), 0, 4))) {
					\sqlsrv_next_result($this->db);
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
		$arr_result = [];
		# Выполняем запрос
		if ($result = $this->_query($query)) {
			$arr_result = [];
			# Если следует использовать ключевое поле
			if ($assoc) {
				$assoc_mark = SQLSRV_FETCH_ASSOC;
			} else {
				$assoc_mark = SQLSRV_FETCH_NUMERIC;
			}
			# Проходим по результату запроса
			while ($array_temp = $this->_data_handler(\sqlsrv_fetch_array($result, $assoc_mark))) {
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

	/** Обработчик результатов запросов */
	protected function _data_handler ($array) {
		if (\is_array($array)) {
			foreach ($array as $k => $v) {
				$array[$k] = $this->{__FUNCTION__}($v);
			}
		} else {
			# Если это строка
			if (\is_string($array)) {
				# То перекодируем
				$array = $this->_win_to_utf($array);
			# Если это объект
			} elseif (\is_object($array)) {
				# Если это дата
				if ('DateTime' == \get_class($array)) {
					$array = \date('Y-m-d H:i:s', $array->getTimestamp());
				}
			}
		}
		return $array;
	}

	/**  Возвращает ID последней вставленной строки или значение последовательности */
	public function insert_id() {
		return $this->_count_rows;
//		return \sqlsrv_rows_affected($result);
	}

	##########################################################
	############## БОЛЬШАЯ ЗАПЛАТКА ##########################
	############## ДЛЯ ПЕРЕВОДА МАССИВА ИЗ MSSQL##############
	############## В КОДИРОВКУ UTF-8 #########################
	##########################################################
	/* Устанавливается порядок определения по массиву */
	protected function _win_to_utf ($array) {
		if (\is_array($array)) {
			foreach ($array as $k => $v) {
				$array[$k] = $this->{__FUNCTION__}($v);
			}
		} else {
			$array = \iconv($this->_db_encoding_db, $this->_db_encoding_sys, $array);
		}
		return $array;
	}
	##########################################################

	##########################################################
	############## БОЛЬШАЯ ЗАПЛАТКА ##########################
	############## ДЛЯ ПЕРЕВОДА МАССИВА ИЗ MSSQL##############
	############## В КОДИРОВКУ UTF-8 #########################
	##########################################################
	/* Устанавливается порядок определения по массиву */
	protected function _utf_to_win ($array) {
		if (\is_array($array)) {
			foreach ($array as $k => $v) {
				$array[$k] = $this->{__FUNCTION__}($v);
			}
		} else {
			$array = iconv($this->_db_encoding_sys, $this->_db_encoding_db, $array);
		}
		return $array;
	}
	##########################################################

/**/
}
