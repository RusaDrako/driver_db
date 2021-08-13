<?php

namespace RusaDrako\driver_db\drivers;





/** <b>BD Sql Driver Class</b> Драйвер работы с БД MySQL (sqlsrv).
 * @version 1.0.0
 * @created 2018-09-27
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
//class mysql_class extends _abstract_class {
class sqlsrv_class implements _interface_class {

	use _trait__get_set;
	use _trait__update;
	use _trait__insert;
	use _trait__delete;
	use _trait__query;
	use _trait__error;



	/** Подключёние к базе данных
	 * @var DB Object */
	private		$db							= false;

	/** Имя сервера */
	private		$_db_server_name			= false;
	/** Порт сервера */
//	private		$_db_server_port			= false;
	/** Имя пользователя */
	private		$_db_user_name				= false;
	/** Пароль доступа */
	private		$_db_password				= false;
	/** Имя БД */
	private		$_db_name_db				= false;
	/** Кодировка системы */
	private		$_db_encoding_sys			= false;
	/** Кодировка БД */
	private		$_db_encoding_db			= false;

	/** Маркер подключения к БД
	 * @var bool */
	private		$_connect					= false;

	/** Число строк затронутых последним запросом */
	private		$_count_rows				= 0;

	private 	$_flag_start = "SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED; ";
	private 	$_flag_finish = "";





	/** Загрузка класса */
	public function __construct(\RusaDrako\driver_db\db_setting $obj_settings) {
		# Настройки подключения к БД
		$this->_db_server_name		= $obj_settings->get_value('host');				# Имя сервера
		if ($port = $obj_settings->get_value('port')) {
			$this->_db_server_name		.= ':' . $port;								# Имя сервера + порт
		}
		$this->_db_user_name		= $obj_settings->get_value('user');				# Имя пользователя
		$this->_db_password			= $obj_settings->get_value('password');			# Пароль доступа
		$this->_db_name_db			= $obj_settings->get_value('db');				# Имя БД
		$this->_db_encoding_sys		= $obj_settings->get_value('encoding_sys');		# Кодировка Системы
		$this->_db_encoding_db		= $obj_settings->get_value('encoding_db');		# Кодировка БД
		# Подключение к БД
		if ($this->_db_connect()) {
			$this->_connect = true;
		};
	}





	/** Выгрузка класса */
    public function __destruct() {
		$this->_db_disconnect();
	}





	/** Функция создания соединения с БД */
	private function _db_connect() {
		# Подключаемся к БД-серверу
		$connectionInfo = ["Database"=>$this->_db_name_db, "UID"=>$this->_db_user_name, "PWD"=>$this->_db_password];
		$db = \sqlsrv_connect($this->_db_server_name, $connectionInfo);
		if (false === $db) {
			$errors = \sqlsrv_errors();
			$num_error = $error['code'];
			$error = $error['message'];
			# Вывод сообщения об ошибке
			throw new \Exception(__FILE__ . '(' . __LINE__ . ') Ошибка подключения к БД: ' . $num_error . ': ' . $error);
		}
		# Присваеваем переменной соединение с БД
		$this->db = $db;
		# Возвращаем результат
		return true;
	}





	/** Функция разрыва соединения с БД */
	private function _db_disconnect() {
		# Если БД подключена
		if (false !== $this->db) {
			# Отключаем БД
			\sqlsrv_close($this->db);
		}
		# Обнуляем переменную
		$this->db = false;
	}





	/** Функция возвращает возвращает результат запроса в БД.
	* @param string $query Строка запроса.
	* @param bool $return_error Маркер возврата сообщения об ошибке.
 	* @return array Ответ БД. */
	private function _query($query) {
		# Если нет подключения к БД, то возвращаем false
		if (!$this->_connect) {return false;}
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
	public function select($query, $assoc = true) {
		# Значение результата по-умолчанию
		$arr_result = [];
		# Выполняем запрос
		if ($result = $this->_query($query)) {
			$arr_result = [];
			# Если следует использовать ключевое поле
			if ($assoc) {
				# Проходим по результату запроса
				while ($array_temp = $this->_data_handler(\sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))) {
					# Формируем массив результатов - с использованием ключа
					$arr_result[] = $array_temp;
				}
			} else {
				# Проходим по результату запроса
				while ($array_temp = $this->_data_handler(\sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC))) {
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
		$v = \htmlspecialchars(\stripslashes(\trim($v)));
		$v = \str_replace("'", '&#039;', $v);
		return $v;
	}





	/** Обработчик результатов запросов */
	private function _data_handler ($array) {
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
	private function _win_to_utf ($array) {
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
	private function _utf_to_win ($array) {
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
