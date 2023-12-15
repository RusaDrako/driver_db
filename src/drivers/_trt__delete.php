<?php

namespace RusaDrako\driver_db\drivers;

trait _trt__delete {

	/**
	 * Удаляет строку из таблицы по условию.
	 * @param string $table_name Имя таблицы.
	 * @param array/string $where Условие обработки строк.
	 * @return bool Ответ БД: true - выполнено; false - не выполнено.
	 */
	public function delete(string $table_name, $where) {
		# Проверка условий прерывания кода
		if (empty($table_name)) {
			# Возвращаем false
			return false;
		}
		# Если нет условия обработки
		if (empty($where)) {
			# Возвращаем false
			return false;
		}
		# Если условие - строка
		if (!is_array($where)) {
			# Формируем массив
			$where = [$where];
		}
		# Формируем запрос
		$sql = "DELETE FROM `" . $table_name . "`
			WHERE " . implode("\n\t\t\t\tAND ", $where);
		$result = $this->_query($sql);
		# Выполняем запрос в БД
		return $result;
	}

/**/
}
