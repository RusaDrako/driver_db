<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД PgSQLL (PDO PostgreSQL).
 * @created 2020-12-15
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class driver_pg_pdo extends _abs_driver_pdo {

	/** @var string Наименование для PDO */
	protected $typeName = 'pgsql';

	/**
	 * @return string
	 */
	public function setDSN() {
		$dsn = parent::setDSN();
		# Настройки DNS
		$dsn .= $this->_db_user ? "user={$this->_db_user};" : '';
		$dsn .= $this->_db_password ? "password={$this->_db_password};" : '';
//		$dsn .= $this->_db_sslmode ? "sslmode={$this->_db_sslmode};" : '';
		return $dsn;
	}

/**/
}
