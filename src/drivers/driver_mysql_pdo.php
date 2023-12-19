<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД MySQL (PDO mysql).
 * @created 2020-12-15
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class driver_mysql_pdo extends _abs_driver_pdo {

	/** @var string Наименование для PDO */
	protected $typeName = 'mysql';

	/** Кодировка */
	protected $_db_encoding;

	/** Установка настроек */
	protected function _set_setting($settings) {
		parent::_set_setting($settings);
		# Настройки подключения к БД
		$this->_db_encoding = $settings['encoding'] ?: null;   # Кодировка
	}

	/**
	 * @return string
	 */
	public function setDSN() {
		$dsn = parent::setDSN();
		# Или host/port, или unix_socket
		$dsn .= ($this->_db_unix_socket && !$this->_db_host && !$this->_db_port) ? "unix_socket={$this->_db_unix_socket};" : '';
		$dsn .= $this->_db_encoding ? "charset={$this->_db_encoding};" : '';
		return $dsn;
	}

/**/
}
