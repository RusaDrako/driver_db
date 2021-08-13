<?php

namespace RusaDrako\driver_db\drivers;





/** <b>BD Sql Driver Class</b> Драйвер работы с БД MySQL (mysql).
 * @version 1.1.0
 * @created 2020-04-27
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class mysql_class implements _interface_class {

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
	/** Кодировка */
	private		$_db_encoding				= false;

	/** Маркер подключения к БД
	 * @var bool */
	private		$_connect					= false;

	/** Число строк затронутых последним запросом */
	private		$_count_rows				= 0;





	/** Загрузка класса */
	public function __construct($obj_settings) {
		# Настройки подключения к БД
		$this->_db_server_name		= $obj_settings->get_value('host');				# Имя сервера
		if ($port = $obj_settings->get_value('port')) {
			$this->_db_server_name		.= ':' . $port;								# Имя сервера + порт
		}
		$this->_db_user_name		= $obj_settings->get_value('user');				# Имя пользователя
		$this->_db_password			= $obj_settings->get_value('password');			# Пароль доступа
		$this->_db_name_db			= $obj_settings->get_value('db');				# Имя БД
		$this->_db_encoding			= $obj_settings->get_value('encoding');			# Кодировка
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
		$db = @\mysql_connect($this->_db_server_name, $this->_db_user_name, $this->_db_password);
		if (!$db) {
			# Выводим сообщение об ошибке
			throw new \Exception(__FILE__ . '(' . __LINE__ . ') Ошибка подключения к БД: ' . \mysql_connect_error());
		} else {
			# Присваеваем переменной соединение с БД
			$this->db = $db;
			# Настройка кодировка
			\mysql_set_charset($this->_db_encoding, $this->db);
			# Подключение заданной БД
			\mysql_select_db($this->_db_name_db, $this->db);
			# Возвращаем результат
			return true;
		}
	}





	/** Функция разрыва соединения с БД */
	private function _db_disconnect() {
		# Если БД подключена
		if (false !== $this->db) {
			# Отключаем БД
			\mysql_close($this->db);
		}
		# Обнуляем переменную
		$this->db = false;
	}





	/** Функция возвращает возвращает результат запроса в БД.
	* @param string $query Строка запроса.
 	* @return array Ответ БД. */
	private function _query($query) {
		# Если нет подключения к БД, то возвращаем false
		if (!$this->_connect) {return false;}
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
	public function select($query, $assoc = true) {
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
