<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Абстрактный класс драйвера.
 * @created 2023-12-15
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
abstract class _abs_driver implements _inf_driver {

	use _trt__get_set;
	use _trt__update;
	use _trt__insert;
	use _trt__delete;
	use _trt__query;
	use _trt__error;

	/** @var DB Подключёние к БД */
	protected $db;

	/** Имя сервера */
	protected $_db_host;
	/** Порт */
	protected $_db_port;
	/** Имя пользователя */
	protected $_db_user;
	/** Пароль доступа */
	protected $_db_password;
	/** Имя БД */
	protected $_db_name;

	/** Число строк затронутых последним запросом */
	protected $_count_rows = 0;

	/** Загрузка класса */
	public function __construct(array $settings){
		# Настройки
		$this->_set_setting($settings);
		# Подключение к БД
		$this->_db_connect();
	}

	/** Установка настроек */
	protected function _set_setting($settings) {
		# Настройки подключения к БД
		$this->_db_host = $settings['host'] ?: null;           # Имя сервера
		$this->_db_port = $settings['port'] ?: null;           # Порт
		$this->_db_user = $settings['user'] ?: null;           # Имя пользователя
		$this->_db_password = $settings['password'] ?: null;   # Пароль доступа
		$this->_db_name = $settings['db'] ?: null;             # Имя БД
	}

	/** Выгрузка класса */
	public function __destruct() {
		$this->_db_disconnect();
	}

	/** Функция создания соединения с БД */
	abstract protected function _db_connect();

	/** Разрывает соединение */
	protected function _db_disconnect() {
		# Обнуляем переменную
		$this->db = NULL;
	}

	/**
	 * Функция возвращает возвращает результат запроса в БД.
	 * @param string $query Строка запроса.
	 * @return array Ответ БД.
	 */
	abstract protected function _query(string $query);

	/**
	 * Функция возвращает массив результата запроса select (массив полей ID) или false.
	 * @param string $query Строка запроса.
	 * @param bool $assoc Возвращать ассоциотивный массив полей
	 * @return array Ответ БД (массив данных).
	 */
	abstract public function select(string $query, bool $assoc = true);

/**/
}

/**
 * Класс ошибки
 */
class DriverDB extends \Exception {}
