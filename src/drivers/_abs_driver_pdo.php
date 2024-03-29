<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Драйвер работы с БД PDO.
 * @created 2020-12-15
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
abstract class _abs_driver_pdo extends _abs_driver {

	/** @var string Наименование для PDO */
	protected $typeName;

//	/** Установка настроек */
//	protected function _set_setting($settings) {
//		parent::_set_setting($settings);
////		$this->_db_host = $this->_db_host . ($this->_db_port ? ":{$this->_db_port}" : '');
//		# Настройки подключения к БД
////		$this->_db_encoding = $settings['encoding'] ?: null;   # Кодировка
//	}

	/** Функция создания соединения с БД */
	protected function _db_connect() {
		$dsn = $this->setDSN();
		# Опции
		$options = $this->setOptions();
		# Подключаемся к БД-серверу
		try {
			$pdo = new \PDO($dsn, $this->_db_user, $this->_db_password, $options);
		} catch (\PDOException $e) {
			# Выводим сообщение об ошибке
			throw new \Exception(__FILE__ . '(' . __LINE__ . ') Ошибка подключения к БД: ' . $e->getMessage());
		}
		# Присваеваем переменной соединение с БД
		$this->db = $pdo;
		# Возвращаем результат
		return true;
	}

	/**
	 * Функция возвращает возвращает результат запроса в БД.
	 * @param string $query Строка запроса.
	 * @return array Ответ БД.
	 */
	protected function _query(string $query) {
		# Значение результата по-умолчанию
		$result = false;
		# Если переменная запроса не пустая
		if (!empty($query)) {
			# Если переменная запроса является строкой
			if (is_string($query)) {
				# Выполнение запроса
				$result = false;
				$result = $this->db->query($query);
				if (\is_bool($result)) {
					$this->_count_rows = 0;
				} else if (\get_class($result) == 'PDOStatement') {
					$this->_count_rows = $result->rowCount();
				} else {
					$this->_count_rows = 0;
				}
				# Получение ошибки запроса
				$num_error = $this->db->errorCode();
				# Если есть ошибка запроса
				if ($num_error
						&& $num_error != '00000') {
					# Ошибка при удалении ключевого элементов, где нет каскадного удаления
					if ('delete ' == substr(mb_strtolower($query), 0, 7)) {
						# Возвращаем false
						return false;
					}
					$error = $this->db->errorInfo();
					# Вывод/генерация сообщения об ошибке
					$this->_error($num_error, $error[2], $query);
				}
			}
		}
		# Возвращаем значение
		return $result;
	}

	/**
	 * Функция возвращает массив результата запроса select (массив полей ID) или false.
	 * @param string $query Строка запроса.
	 * @param bool $assoc Возвращать ассоциотивный массив полей
	 * @return array Ответ БД (массив данных).
	 */
	public function select(string $query, bool $assoc = true) {
		# Значение результата по-умолчанию
		$arr_result = [];
		# Выполняем запрос
		if ($result = $this->_query($query)) {
			$arr_result = [];
			# Если следует использовать ключевое поле
			if ($assoc) {
				$assoc_mark = \PDO::FETCH_ASSOC;
			} else {
				$assoc_mark = \PDO::FETCH_NUM;
			}
			# Проходим по результату запроса
			while ($row = $result->fetch($assoc_mark)) {
				$arr_result[] = $row;
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
		return $this->db->lastInsertId();
	}

	/**
	 * @return string
	 */
	public function setDSN() {
		# Настройки DNS
		$dsn = "{$this->typeName}:";
		$dsn .= $this->_db_host ? "host={$this->_db_host};" : '';
		$dsn .= $this->_db_port ? "port={$this->_db_port};" : '';
		$dsn .= $this->_db_name ? "dbname={$this->_db_name};" : '';
		return $dsn;
	}

	/**
	 * @return array
	 */
	public function setOptions() {
		return [];
	}

}
