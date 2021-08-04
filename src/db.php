<?php

namespace RusaDrako\driver_db;

require_once('db_setting.php');





class db {

	/** Объект драйвера */
	protected $_obj_driver			= null;
	/** Объект модели */
	private static $_object		= [];





	/** Загрузка класса */
	public function __construct($prefix, $reg = null) {
		$class_name = '\\' . __NAMESPACE__ . '\\db_setting';
		$_obj_setting = new $class_name($prefix, $reg);
		$this->_obj_driver = $_obj_setting->get_driver();
		if (!$this->_obj_driver) {
			die('<b>' . __FILE__ . '(' . __LINE__ . ')</b> Не найден драйвер подклячения к БД для префикса ' . $prefix);
		}
	}





	/** Выгрузка класса */
	public function __destruct () {}





	/** Вызов объекта
	* @param object $db Объект драйвера подключения к БД
	* @return object Объект модели
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





	/** Запускается при вызове недоступных методов в контексте объект */
	public function __call($name, $args) {
//--		try {
			# Проверяем существование метода (не существует)
			if (!\method_exists($this->_obj_driver, $name)) {
				throw new \Exception("Метод " . \get_class($this->_obj_driver) . "->{$name}() не определён");
			}
			return $this->_obj_driver->$name(...$args);
//--		} catch (\Exception $e) {
//--			echo '<hr>';
//--			echo '<b>' . $e->getFile() . ' (' . $e->getLine() . ')</b> ' . $e->getMessage();
//--			echo '<hr>';
//--			exit;
//--		}
	}





	/** Запускается при вызове недоступных методов в статическом контексте */
	public static function __callStatic($name, $args) {
		# Если имя класса не определено
		if (false === self::$_class_name) {
			# Определяем его через специальную функцию
			self::__get_class_name();
		}
		# Получаем для удобства имя класса в переменную
		$class_name = self::$_class_name;
		# Проверяем существование метода (не существует)
		try {
			if (!method_exists($class_name, $name)) {
				throw new \Exception("Метод {$class_name}::{$name}() не определён");
			}
			# Создаём объект метода
			$refMethod = new ReflectionMethod($class_name, $name);
			# Запускаем метод с аргументами (без объекта)
			return $refMethod->invokeArgs(NULL, $args);
		} catch (\Exception $e) {
			echo '<hr>';
			echo '<b>' . $e->getFile() . ' (' . $e->getLine() . ')</b> ' . $e->getMessage();
			echo '<hr>';
			exit;
		}
	}





	/** Присваеваеми значениее свойства */
	public function __set($name, $value) {
		#
		return $this->_obj->$name = $value;
	}





	/** Получаем значениее свойства */
	public function __get($name) {
		return $this->_obj->$name;
	}





	/** */
	public function __isset($name) {
		return isset($this->_obj->$name);
	}





	/** */
	public function __unset($name) {
		unset($this->_obj->$name);
	}





	/** Возвращает объект подключённого драйвера */
	public function get_driver() {
		return $this->_obj_driver;
	}





/**/
}
