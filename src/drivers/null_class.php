<?php

namespace RusaDrako\driver_db\drivers;





/**
 * @version 1.0.0
 * @created 2019-06-21
 * @author Петухов Леонид <petuhov.leonid@cube8.ru>
 */
class null_class implements _interface_class {





	/** Загрузка класса
	 * @param array $settings = [] - массив настроек
	 * @return null*/
	public function __construct(\RusaDrako\driver_db\db_setting $settings) {

	}





	/** Cоздаёт строку в таблице с заданными переменными.
	 * @param string $table_name Имя таблицы.
	 * @param array $arr_insert Массив с переменными для добавления.
	 * @param array $arr_where Условие добавления строки.
	 * @return array Ответ БД: ID номер новой строки или false. */
	public function insert($table_name, $arr_insert, $arr_where) {
		return 0;
	}





	/**  Возвращает ID последней вставленной строки или значение последовательности */
	public function insert_id() {
		return 0;
	}





	/** Функция возвращает возвращает результат запроса в БД.
	 * @param string $query Строка запроса.
	 * @param array $data Массив данных для запроса
	 * @return array Ответ БД.
	 */
	public function query(string $query, array $data) {
		return 0;
	}





	/** Функция возвращает массив результата запроса select (массив полей ID) или false.
	 * @param string $query Строка запроса.
	 * @param array $data Массив данных для запроса
	 * @return array Ответ БД (массив данных).
	 */
	public function select(string $query, array $data = [], bool $assoc = true) {
		return 0;
	}





	/** Обновляет строку в таблице масивом переменных по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array $arr_update Массив с переменными для обновления.
	 * @param array $arr_where Условие обработки строк.
	 * @return bool Ответ БД: true или false.
	 */
	public function update(string $table_name, array $arr_update, $arr_where) {
		return 0;
	}





	/** Удаляет строку из таблицы по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array $where Условие обработки строк.
	 * @return bool Ответ БД: true - выполнено; false - не выполнено.
	 */
	public function delete(string $table_name, $arr_where) {
		return 0;
	}





/**/
}
