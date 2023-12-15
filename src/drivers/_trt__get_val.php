<?php

namespace RusaDrako\driver_db\drivers;

trait _trt__get_val {

	/**
	 * Обновляет строку в таблице масивом переменных по условию.
	 * @param array $array Массив с переменными.
	 */
	public function _db_get_val($array) {
		# Нулевой массив результата
		$result = [
			'col' => [],
			'val' => [],
		];
		# Проходим по массиву обновления
		foreach ($array as $k => $v) {
			$result['col'][] = "`{$k}`";
			# Если значение null
			if (is_null($v)) {
				# Прописываем NULL
				$result['val'][] = "NULL";
				# Если значение - функция
			} elseif (is_callable($v)) {
				# Прописываем NULL
				$result['val'][] = "{$_v}";
				# Если значение массив или объект
			} elseif (is_array($v)
			|| is_object($v)) {
				# Формируем JSON-строку
				$result['val'][] = "'" . json_encode($v) . "'";
			} else {
				$_v = $this->_db_get_set_clean($v);
				# Строка по умолчанию
				$result['val'][] = "'{$_v}'";
			}
		}
		# Возвращаем результат
		return $result;
	}

	/**
	 * Обновляет строку в таблице масивом переменных по условию.
	 * @param array $array Массив с переменными.
	 */
	public function _db_get_val_ins($array) {
		# Нулевой массив результата
		$result = [
		'col' => [],
		'val' => [],
		];
		# Проходим по массиву обновления
		foreach ($array as $k => $v) {
			$result['col'][] = "`{$k}`";
			# Если значение null
			if (is_null($v)) {
				# Прописываем NULL
				$result['val'][] = "NULL";
				# Если значение массив или объект
			} elseif (is_array($v)
			|| is_object($v)) {
				# Формируем JSON-строку
				$result['val'][] = "'" . json_encode($v) . "'";
			} else {
				$_v = $this->_db_get_set_clean($v);
				# Строка по умолчанию
				$result['val'][] = "'{$_v}'";
			}
		}
		# Возвращаем результат
		return $result;
	}

/**/
}
