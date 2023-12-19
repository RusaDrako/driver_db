<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД SQLite3 (PDO SQLite3).
 * @created 2020-12-15
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class driver_sqlite3_pdo extends _abs_driver_pdo {

	use _trt__get_val;
	use _trt__insert_val;

	/** @var string Наименование для PDO */
	protected $typeName = 'sqlite';

	/**
	 * @return string
	 */
	public function setDSN() {
		# Настройки DNS
		$dsn = "{$this->typeName}:";
		$dsn .= $this->_db_host ?: '';
		return $dsn;
	}

/**/
}
