<?php

namespace RusaDrako\driver_db\drivers;

trait _trt__get_set {

	/**
	 * Обновляет строку в таблице масивом переменных по условию.
	 * @param array $array Массив с переменными.
	 */
	public function _db_get_set($array) {
		# Нулевой массив результата
		$result = [];
		# Проходим по массиву обновления
		foreach ($array as $k => $v) {
			# Если значение null
			if (is_null($v)) {
				# Прописываем NULL
				$result[] = "`{$k}` = NULL";
			# Если значение - функция
			} elseif (is_callable($v)) {
				# Прописываем NULL
				$result[] = "`{$k}` = {$v()}";
			# Если значение массив или объект
			} elseif (is_array($v)
					|| is_object($v)) {
				# Формируем JSON-строку
				$result[] = "`{$k}` = '" . json_encode($v) . "'";
			} else {
				$_v = $this->_db_get_set_clean($v);
				# Строка по умолчанию
				$result[] = "`{$k}` = '{$_v}'";
			}
		}
		# Возвращаем результат
		return $result;
	}

	/**
	 * Обновляет строку в таблице масивом переменных по условию.
	 * @param array $array Массив с переменными.
	 */
	public function _db_get_set_ins($array) {
		# Нулевой массив результата
		$result = [];
		# Проходим по массиву обновления
		foreach ($array as $k => $v) {
			# Если значение null
			if (is_null($v)) {
				# Прописываем NULL
				$result[] = "`{$k}` = NULL";
			# Если значение массив или объект
			} elseif (is_array($v)
					|| is_object($v)) {
				# Формируем JSON-строку
				$result[] = "`{$k}` = '" . json_encode($v) . "'";
			} else {
				$_v = $this->_db_get_set_clean($v);
				# Строка по умолчанию
				$result[] = "`{$k}` = '{$_v}'";
			}
		}
		# Возвращаем результат
		return $result;
	}

/**/
}
