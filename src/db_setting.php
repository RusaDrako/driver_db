<?php

namespace RusaDrako\driver_db;





/** */
class db_setting {
	/** Расширение шаблона */
	private $_setting = [];





	/** Конструктор объекта (загрузка настроек) */
	public function __construct($prefix, $reg = null) {
		if ($reg === null) {
			$reg = \registry::call();
		}
		$arr_set = [
			'driver'         => $reg->get($prefix . '_DRIVER',         'mysqli'),      # Драйвер БД
			'host'           => $reg->get($prefix . '_HOST',           'localhost'),   # Имя сервера
			'port'           => $reg->get($prefix . '_PORT',           null),          # Порт
			'user'           => $reg->get($prefix . '_USER',           'root'),        # Имя пользователя
			'password'       => $reg->get($prefix . '_PASS',           ''),            # Пароль доступа
			'db'             => $reg->get($prefix . '_DBNAME',         null),          # Имя БД
			'encoding'       => $reg->get($prefix . '_ENCODING',       'utf8'),        # Кодировка (mysql)
			'encoding_sys'   => $reg->get($prefix . '_ENCODING_SYS',   'UTF-8'),       # Кодировка Системы (mssql)
			'encoding_db'    => $reg->get($prefix . '_ENCODING_DB',    'CP1251'),      # Кодировка БД (mssql)
		];
		$this->_setting = $arr_set;
	}





	/** Деструктор класса */
	public function __destruct() {}





	/** */
	public function get_value($name) {
		if (isset($this->_setting[$name])) {
			return $this->_setting[$name];
		}
		return null;
	}





	/** Возвращает объект подключённого драйвера */
	public function get_driver() {
		$driver_name = $this->_setting['driver'] . '_class';
		$driver_file = __DIR__ . '/drivers/' . $driver_name . '.php';
		if (\file_exists($driver_file)) {
			require_once($driver_file);
			$class_name = '\\' . __NAMESPACE__ . '\\drivers\\' . $driver_name;
			$_obj = new $class_name($this);
			return $_obj;
		}
		return null;
	}





/**/
}
