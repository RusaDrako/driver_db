<?php

namespace RusaDrako\driver_db;

/**
 * Настройки для подключения
 */
class SettingDB {

	/** Настройки */
	private $_setting = [];

	/**
	 * Возвращает значение свойства по имени
	 */
	public function set(string $dbName, array $setting) {
		$arr_set = [
			'driver'       => $setting['DRIVER'] ?: CoreDB::DRV_MYSQLI, # Драйвер БД
			'host'         => $setting['HOST'] ?: 'localhost',          # Имя сервера
			'port'         => $setting['PORT'] ?: null,                 # Порт
			'user'         => $setting['USER'] ?: '',                   # Имя пользователя
			'password'     => $setting['PASS'] ?: '',                   # Пароль доступа
			'db'           => $setting['DBNAME'] ?:  null,              # Имя БД
			'encoding'     => $setting['ENCODING'] ?: 'utf8mb4',        # Кодировка (mysql)
			'encoding_sys' => $setting['ENCODING_SYS'] ?: 'UTF-8',      # Кодировка Системы (mssql)
			'encoding_db'  => $setting['ENCODING_DB'] ?: 'CP1251',      # Кодировка БД (mssql)
		];
		$this->_setting[$dbName] = $arr_set;
	}

	/** Возвращает имя класса драйвера */
	public function getDriverObject(string $dbName) {
		if(!array_key_exists($dbName, $this->_setting)) {
			throw new ExceptionSettingDB("Настройки подключения к БД '{$dbName}' не найдены.");
		}
		$className = '\\' . __NAMESPACE__ . '\\drivers\\driver_' . $this->_setting[$dbName]['driver'];

		$object = new $className($this->_setting[$dbName]);
		return $object;
	}

	///** Возвращает значение свойства по имени */
	//public function get_value($name) {
	//	if (isset($this->_setting[$name])) {
	//		return $this->_setting[$name];
	//	}
	//	return null;
	//}
	//
	///** Возвращает имя класса драйвера */
	//private function get_driver_name() {
	//	return $this->_setting['driver'] . '_class';
	//}
	//
	///** Возвращает имя класса драйвера */
	//private function get_driver_name() {
	//	return $this->_setting['driver'] . '_class';
	//}
	//
	///** Возвращает имя класса драйвера */
	//public function get_driver_file() {
	//	return __DIR__ . '/drivers/' . $this->get_driver_name() . '.php';
	//}
	//
	///** Возвращает имя класса драйвера */
	//public function get_driver_class() {
	//	return '\\' . __NAMESPACE__ . '\\drivers\\' . $this->get_driver_name();
	//}

/**/
}

/**
 * Класс ошибки
 */
class ExceptionSettingDB extends \Exception {}
