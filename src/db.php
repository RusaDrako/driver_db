<?php

namespace RusaDrako\driver_db;

require_once('db_setting.php');




/** Базовый класс подключения */
class db {

	/** Объект настроек */
	protected $_obj_setting   = null;
	/** Объект драйвера */
	protected $_obj_driver    = null;
	/** Объект модели */
	private static $_object   = [];





	/** Загрузка класса
	 * @param string $prefix Префикс имён для получения данных из объекта $reg
	 * @param object $reg Объект данных реализующий метод get($name, $def_value)
	 */
	public function __construct(string $prefix, $reg = null) {
		# Если отсутствует объект данных
		if (!$reg) { return; }
		# Получаем настройки подключения
		$this->set_setting_object($prefix, $reg);
		# Получаем подключение
		$this->set_driver_object();
	}





	/** Вызов объекта
	 * @param string $prefix Префикс имён для получения данных из объекта $reg
	 * @param object $reg Объект данных реализующий метод get($name, $def_value)
	 */
	public static function call(...$args) {
		# Получаем имя класса, который вызвал этот метод
		# для родительского метода это необходимо,
		# т.к. иначе класс будет хватать первый созданный объект
		# Проверяем отсутствие объекта заданного класса в массиве объектов
		if (!isset(self::$_object[$args[0]])) {
			# Создаём объект и заносим его в массив
			self::$_object[$args[0]] = new static(...$args);
		}
		# Возвращаем указанный объект
		return self::$_object[$args[0]];
	}





	/** Создаёт объект настроек подключения и заполняет его
	 * @param string $prefix Префикс имён для получения данных из объекта $reg
	 * @param object $reg Объект данных реализующий метод get($name, $def_value)
	 */
	public function set_setting_object(string $prefix, object $reg) {
		$class_name = '\\' . __NAMESPACE__ . '\\db_setting';
		$this->_obj_setting = new $class_name();
		$this->_obj_setting->set_setting($prefix, $reg);
	}





	/** Загружает объект драйвера подключения к БД */
	public function set_driver_object() {
		if (!$this->_obj_setting) {
			throw new \Exception("Отсутствуют настройки подключения к БД");
		}
		try {
			$result = $this->_load_driver_object();
		} catch (\Exception $e) {
			throw new \Exception("Не удалось подключить драйвер БД: {$e->getMessage()} {$e->getFile()} ({$e->getLine()})");
		}
		$this->set_driver($result);
		return $this->_obj_driver;
	}





	/** Возвращает имя класса настроек */
	private function _load_driver_object() {
		# Получаем имя файла драйвера
		$driver_file = $this->_obj_setting->get_driver_file();
		# Проверяем существование файла драйвера
		if (!\file_exists($driver_file)) {
			throw new \Exception("Файл драйвера не найден: {$driver_file}");
		}
		# Подгружаем драйвер
		require_once($driver_file);
		# Получаем имя класса драйвера
		$class_name = $this->_obj_setting->get_driver_class();
		# Проверяем существование файла драйвера
		if (!\class_exists($class_name)) {
			throw new \Exception("Класс драйвера не найден: {$class_name}");
		}
		# Активируем класс
		$_obj = new $class_name($this->_obj_setting);
		# Возвращаем класс
		return $_obj;
	}





	/** Возвращает объект подключённого драйвера
	 * @param \RusaDrako\driver_db\drivers\_interface_class $obj Объект драйвера подключения к БД
	 */
	public function set_driver(\RusaDrako\driver_db\drivers\_interface_class $obj) {
		$this->_obj_driver = $obj;
	}





	/* * Возвращает объект подключённого драйвера * /
	public function get_driver() {
		return $this->_obj_driver;
	}










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
	public function select(string $query, bool $assoc = true) {
		return $this->_obj_driver->select($query, $assoc);
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
