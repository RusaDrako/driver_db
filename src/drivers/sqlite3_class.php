<?php

namespace RusaDrako\driver_db\drivers;





/** <b>BD Sql Driver Class</b> Драйвер работы с БД SQLite (sqlsrv).
 * @version 1.0.0
 * @created 2022-04-08
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class sqlite3_class implements _interface_class {

	use _trait__get_val;
	use _trait__update;
	use _trait__insert_val;
	use _trait__delete;
	use _trait__query;
	use _trait__error;



	/** Подключёние к базе данных
	 * @var DB Object */
	private		$db							= false;

	/** Имя БД */
	private		$_db_name_db				= false;

	/** Маркер подключения к БД
	 * @var bool */
	private		$_connect					= false;

	/** Число строк затронутых последним запросом */
	private		$_count_rows				= 0;





	/** Загрузка класса */
	public function __construct(\RusaDrako\driver_db\db_setting $obj_settings) {
		# Настройки подключения к БД
		$this->_db_name_db       = $obj_settings->get_value('db');     # Имя БД
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
		# Подключаемся к БД
		$db = new \SQLite3($this->_db_name_db);
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
			$this->db->close();
		}
		# Обнуляем переменную
		$this->db = false;
	}





	/** Функция возвращает возвращает результат запроса в БД.
	* @param string $query Строка запроса.
	* @param bool $return_error Маркер возврата сообщения об ошибке.
 	* @return array Ответ БД.
	*/
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





	/**  Возвращает ID последней вставленной строки или значение последовательности */
	public function insert_id() {
		return $this->_count_rows;
	}





/**/
}
