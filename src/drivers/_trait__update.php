<?php
namespace RusaDrako\driver_db\drivers;



trait _trait__update {



	/** Обновляет строку в таблице масивом переменных по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array $array_update Массив с переменными для обновления.
	 * @param array/string $where Условие обработки строк.
	 * @return bool true или false. */
	public function update($table_name, $array_update, $where) {
		# Проверка условий прерывания кода
		if (empty($table_name)
				|| empty($array_update)
				|| empty($where)) {
			# Возвращаем false
			return false;
		}
		# Если условие - строка
		if (!is_array($where)) {
			# Формируем массив
			$where = [$where];
		}
		# Формируем поля обновления
		$arr_update_sql = $this->_db_get_set($array_update);
		# Формируем запрос
		$sql = "UPDATE `{$table_name}`
			SET " . implode(',', $arr_update_sql) . "
			WHERE " . implode("\n\t\t\t\tAND ", $where);
//\core\print_info($sql);
		# Выполняем запрос
		$result = $this->_query($sql);
		# Возвращаем результат
		return $this->_count_rows;
	}



/**/
}
