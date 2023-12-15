<?php

namespace RusaDrako\driver_db;

/**
 * Расширение БД
 */
class DB extends CoreDB {

	/** Возвращает результат запроса в БД.
	 * @param string $query Строка запроса
	 * @param array $data Массив данных для запроса
 	 * @return array Ответ БД.
	 */
	public function query(string $query, array $data = []) {
		return $this->_obj_driver->query($query, $data);
	}

	/** Cоздаёт строку в таблице с заданными переменными.
	 * @param string $table_name Имя таблицы.
	 * @param array $array_insert Массив с переменными для добавления.
	 * @param array/string $where Условие добавления строки.
	 * @return array Ответ БД (номер новой строки) или false.
	 */
	public function insert(string $table_name, array $array_insert, $where = []) {
		return $this->_obj_driver->insert($table_name, $array_insert, $where);
	}

	/** Функция возвращает массив результата запроса select (массив полей ID) или false.
	 * @param string $query Строка запроса.
	 * @param array $data Массив данных для запроса
	 * @param string $assoc Ассоциативный массив.
	 * @return array Ответ БД (массив данных).
	 */
	public function select(string $query, $assoc = true) {
		return $this->_obj_driver->select($query, (bool) $assoc);
	}

	/** Обновляет строку в таблице масивом переменных по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array $array_update Массив с переменными для обновления.
	 * @param array/string $where Условие обработки строк.
	 * @return bool true или false.
	 */
	public function update(string $table_name, array $array_update, $where) {
		return $this->_obj_driver->update($table_name, $array_update, $where);
	}

	/** Удаляет строку из таблицы по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array/string $where Условие обработки строк.
	 * @return bool Ответ БД: true - выполнено; false - не выполнено.
	 */
	public function delete(string $table_name, $where) {
		return $this->_obj_driver->delete($table_name, $where);
	}

/**/
}
