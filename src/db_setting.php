<?php

namespace RusaDrako\driver_db;





/** Настройки для конкретного подключения */
class db_setting {
	/** Расширение шаблона */
	private $_setting = [];





	/** Возвращает значение свойства по имени
	 * @param string $prefix Префикс имён для получения данных из объекта $reg
	 * @param object $reg Объект данных реализующий метод get($name, $def_value)
	 */
	public function set_setting(string $prefix, $reg) {
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





	/** Возвращает значение свойства по имени */
	public function get_value($name) {
		if (isset($this->_setting[$name])) {
			return $this->_setting[$name];
		}
		return null;
	}





	/** Возвращает имя класса драйвера */
	private function get_driver_name() {
		return $this->_setting['driver'] . '_class';
	}





	/** Возвращает имя класса драйвера */
	public function get_driver_file() {
		return __DIR__ . '/drivers/' . $this->get_driver_name() . '.php';
	}





	/** Возвращает имя класса драйвера */
	public function get_driver_class() {
		return '\\' . __NAMESPACE__ . '\\drivers\\' . $this->get_driver_name();
	}



/**/
}
