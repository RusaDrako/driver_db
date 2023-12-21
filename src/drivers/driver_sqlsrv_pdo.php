<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД MsSQL (PDO sqlsrv).
 * @created 2020-12-15
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class driver_sqlsrv_pdo extends _abs_driver_pdo {

	/** @var string Наименование для PDO */
	protected $typeName = 'sqlsrv';

	/** Кодировка системы */
	protected $_db_encoding_sys;
	///** Кодировка БД */
	//protected $_db_encoding_db;

	/** Установка настроек */
	protected function _set_setting($settings) {
		parent::_set_setting($settings);
		# Настройки подключения к БД
		$this->_db_encoding_sys = $settings['encoding_sys'] ?: null;   # Кодировка Системы
//		$this->_db_encoding_db = $settings['encoding_db'] ?: null;     # Кодировка БД
	}

	/**
	 * @return string
	 */
	public function setDSN() {
		# Настройки DNS
		$dsn = "{$this->typeName}:";
		$dsn .= $this->_db_host ? "Server={$this->_db_host}" . ($this->_db_port ? ",{$this->_db_port}" : '') . ';' : '';
		$dsn .= $this->_db_name ? "Database={$this->_db_name};" : '';
		return $dsn;
	}

	/**
	 * @return array
	 */
	public function setOptions() {
		return [];
		//return [
		//	\PDO::SQLSRV_ATTR_ENCODING => \PDO::SQLSRV_ENCODING_UTF8,
		//	"CharacterSet"             => $this->_db_encoding_sys,
		//];
	}

/**/
}
