<?php

namespace RusaDrako\driver_db\drivers;





/** <b>BD Sql Driver Class</b> Драйвер работы с БД MySQL (PDO sqlsrv).
 * @version 1.1.0
 * @created 2020-04-27
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class sqlsrv_pdo_class implements _interface_class {

	use _trait__get_set;
	use _trait__update;
	use _trait__insert;
	use _trait__delete;
	use _trait__query;
	use _trait__error;



	/** Подключёние к базе данных
	 * @var DB Object */
	private		$_pdo						= false;

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
	/** Кодировка Системы */
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
		# Получаем версию PHP и разбиваем её в массив по точке
		$arr_php_vertion = explode('.',phpversion());
		# Настройки подключения к БД
		$this->_db_server_name		= $obj_settings->get_value('host');						# Имя сервера
		if ($port = $obj_settings->get_value('port')) {
			$this->_db_server_name		.= ':' . $port;										# Имя сервера + порт
		}
		$this->_db_user_name		= $obj_settings->get_value('user');						# Имя пользователя
		$this->_db_password			= $obj_settings->get_value('password');					# Пароль доступа
		$this->_db_name_db			= $obj_settings->get_value('db');						# Имя БД
		$this->_db_encoding_sys		= $obj_settings->get_value('encoding_sys', 'UTF-8');	# Кодировка Системы
		$this->_db_encoding_db		= $obj_settings->get_value('encoding_db');				# Кодировка БД
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
		# Настройки DNS
		$dsn = "sqlsrv:Server=" . $this->_db_server_name . ";Database=" . $this->_db_name_db . ";";
//		print_info(new \PDO());
		# Опции
		$options = [
			\PDO::SQLSRV_ATTR_ENCODING	=> \PDO::SQLSRV_ENCODING_UTF8,
			"CharacterSet"				=> $this->_db_encoding_sys,
		];
		try {
			$pdo = new \PDO ($dsn, $this->_db_user_name, $this->_db_password, $options);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		# Ловим ошибку
		} catch (\PDOException $e) {
			# Выводим сообщение об ошибке
			throw new \Exception(__FILE__ . '(' . __LINE__ . ') Ошибка подключения к БД: ' . $e->getMessage());
		}
		# Присваеваем переменной соединение с БД
		$this->_pdo = $pdo;
		# Возвращаем результат
		return true;
	}


	/** Функция разрыва соединения с БД */
	private function _db_disconnect() {
		# Обнуляем переменную
		$this->_pdo = false;
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
				$result = false;
				$result = $this->_pdo->query($this->_flag_start . $query . $this->_flag_finish);
				if (\is_bool($result)) {
					$this->_count_rows = 0;
				} else if (\get_class($result) == 'PDOStatement') {
					$this->_count_rows = $result->rowCount();
				} else {
					$this->_count_rows = 0;
				}
				# Получение ошибки запроса
				$num_error = $this->_pdo->errorCode();
				# Если есть ошибка запроса
				if ($num_error
						&& $num_error !=	'00000') {
					# Ошибка при удалении ключевого элементов, где нет каскадного удаления
					if ('delete ' == substr(mb_strtolower($query), 0, 7)) {
						# Возвращаем false
						return false;
					}
					$error = $this->_pdo->errorInfo();
					# Вывод/генерация сообщения об ошибке
					$this->_error($num_error, $error, $query);
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
			if ($assoc) {
				while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
					$arr_result[] = $row;
				}
			} else {
				while ($row = $result->fetch(\PDO::FETCH_NUM)) {
					$arr_result[] = $row;
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
		$v = \addslashes($v);
		return $v;
	}





	/**  Возвращает ID последней вставленной строки или значение последовательности */
	public function insert_id() {
		return $this->_pdo->lastInsertId();
	}



/**/
}
