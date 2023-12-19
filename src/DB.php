<?php

namespace RusaDrako\driver_db;

/**
 * Класс подключения к БД
 */
class DB {

	/** Типы драйверов */
	const DRV_MYSQL = 'mysql';
	const DRV_MYSQLI = 'mysqli';
	const DRV_MYSQL_PDO = 'mysql_pdo';
	const DRV_SQLITE3 = 'sqlite3';
	const DRV_SQLITE3_PDO = 'sqlite3_pdo';
	const DRV_SQLSRV = 'sqlsrv';
	const DRV_SQLSRV_PDO = 'sqlsrv_pdo';
	const DRV_PG_PDO = 'pg_pdo';

	/** @var SettingDB|null Объект настроек */
	protected $_objSetting = null;
	/** @var array Соединения */
	protected $_arrCoccnects = [];

	/** Загрузка класса */
	public function __construct() {
		$this->_objSetting = new SettingDB();
	}

	/** Устанавливает настройки подключения */
	public function setDB(string $dbName, array $settings) {
		$this->_objSetting->set($dbName, $settings);
		unset($this->_objDriver[$dbName]);
	}

	/**  */
	public function getDBConnect(string $dbName) {
		if (!array_key_exists($dbName, $this->_arrCoccnects)) {
			$this->_arrCoccnects[$dbName] = $this->_objSetting->getDriverObject($dbName);
		}
		return $this->_arrCoccnects[$dbName];
	}

/**/
}
