<?php

namespace RusaDrako\driver_db\drivers;

trait _trt__insert {

	/**
	 * Cоздаёт строку в таблице с заданными переменными.
	 * @param string $table_name Имя таблицы.
	 * @param array $array_insert Массив с переменными для добавления.
	 * @param array $arr_where Условие добавления строки.
	 * @return array Ответ БД (номер новой строки) или false.
	 */
	public function insert(string $table_name, array $array_insert, $arr_where = []) {
		# Проверка условий прерывания кода
		if (empty($table_name)) {
			# Возвращаем false
			return false;
		}
		$str_where = '';
		# Если условие - строка
		if (!is_array($arr_where)) {
			# Формируем массив
			$arr_where = [$arr_where];
		}
		if ($arr_where) {
			$str_where = ' WHERE ' . implode("\nAND ", $arr_where);
		}
		# Формируем поля обновления
		$arr_insert_sql = $this->_db_get_set_ins($array_insert);
		# Формируем запрос
		$sql = "INSERT INTO `{$table_name}`
			SET " . implode(', ', $arr_insert_sql) . "
			{$str_where}"
			;
/*		if (empty($arr_insert_sql)) {
			$sql = "INSERT INTO `{$table_name}` () VALUES ()";
		}/**/
		# Нулевое значение ключа
		$new_id = false;
		# Если запрос прошёл нормально
		if ($result = $this->_query($sql)) {
			# Получаем ID новой строки
			$new_id = $this->insert_id($result);
//			$new_id = mysqli_insert_id($this->db);
		}
		# Возвращаем результат
		return $new_id;
	}

/**/
}
