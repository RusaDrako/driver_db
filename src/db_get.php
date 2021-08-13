<?php

namespace RusaDrako\driver_db;





/** Класс хранения переменных */
class db_get {
	/**
	 * @param array $_keys Массив ключей со значениями
	 */
	private $_value             = [];





	/** */
	public function __debugInfo() {
		return $this->_value;
	}





	/** Заполняет/обновляет ключ регистра
	 * @param string $key Ключ регистра
	 * @param string/bool/int $value Значение регистра
	 * @return bool true - успешно, false - ошибка
	 */
	public function set($key, $value) {
		$this->_value[$key] = $value;
		return true;
	}





	/** Возвращает ключ регистра
	 * @param string $key Ключ регистра
	 * @param string/bool/int/null $default Значение по умолчанию (null)
	 * @return string/bool/int/null Значение ключа или значение переменной $default
	 */
	public function get($key, $default = null) {
		if (isset($this->_value[$key])) {
			return  $this->_value[$key];
		}
		return $default;
	}





/**/
}
