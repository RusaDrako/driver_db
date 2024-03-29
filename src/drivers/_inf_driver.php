<?php

namespace RusaDrako\driver_db\drivers;

/**
 * Интерфайс драйвера работы с БД
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
interface _inf_driver {

	/**
	 * Загрузка класса
	 * @param array $settings = [] - массив настроек
	 * @return null
	 */
	public function __construct(array $settings);

	/**
	 * Cоздаёт строку в таблице с заданными переменными.
	 * @param string $table_name Имя таблицы.
	 * @param array $arr_insert Массив с переменными для добавления.
	 * @param array $arr_where Условие добавления строки.
	 * @return array Ответ БД: ID номер новой строки или false.
	 */
	public function insert(string $table_name, array $arr_insert, $arr_where = []);

	/**
	 * Возвращает ID последней вставленной строки или значение последовательности
	 */
	public function insert_id();

	/**
	 * Функция возвращает возвращает результат запроса в БД.
	 * @param string $query Строка запроса.
	 * @param bool $return_error Маркер возврата сообщения об ошибке.
 	 * @return array Ответ БД.
	 */
	public function query(string $query);

	/**
	 * Функция возвращает массив результата запроса select (массив полей ID) или false.
	 * @param string $query Строка запроса.
	 * @param string $key Ключ поля, которое следует использовать в качестве ключей массива.
	 * @return array Ответ БД (массив данных).
	 */
	public function select(string $query, bool $assoc = true);

	/**
	 * Обновляет строку в таблице масивом переменных по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array $arr_update Массив с переменными для обновления.
	 * @param array $arr_where Условие обработки строк.
	 * @return bool Ответ БД: true или false.
	 */
	public function update(string $table_name, array $arr_update, $arr_where);

	/**
	 * Удаляет строку из таблицы по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array $where Условие обработки строк.
	 * @return bool Ответ БД: true - выполнено; false - не выполнено.
	 */
	public function delete(string $table_name, $arr_where);

/**/
}
