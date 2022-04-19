<?php
namespace RusaDrako\driver_db\drivers;



trait _trait__insert_val {



	/** Cоздаёт строку в таблице с заданными переменными.
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
		$arr_insert_sql = $this->_db_get_val_ins($array_insert);
		# Формируем запрос
		$sql = "INSERT INTO `{$table_name}` (" . implode(', ', $arr_insert_sql['col']) . ")
			VALUES (" . implode(', ', $arr_insert_sql['val']) . ")
			{$str_where}"
			;
		# Нулевое значение ключа
		$new_id = false;
		# Если запрос прошёл нормально
		if ($result = $this->_query($sql)) {
			# Получаем ID новой строки
			$new_id = $this->insert_id($result);
		}
		# Возвращаем результат
		return $new_id;
	}



/**/
}
