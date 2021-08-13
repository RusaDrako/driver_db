<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/autoload.php');





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 */
class db_setting_Test extends TestCase {
	/** */
	private $class_name_test = 'RusaDrako\driver_db\db_setting';
	private $class_name_get = 'RusaDrako\driver_db\db_get';
	private $arr_setting_data = [
		# Драйвер БД
		'DRIVER'       => [
			'set_title' => 'driver',
			'data'      => 'empty',
			'default'   => 'mysqli',
		],
		# Имя сервера
		'HOST'         => [
			'set_title' => 'host',
			'data'      => 'localhost1',
			'default'   => 'localhost',
		],
		# Порт
		'PORT'         => [
			'set_title' => 'port',
			'data'      => '9999',
			'default'   => '',
		],
		# Имя пользователя
		'USER'         => [
			'set_title' => 'user',
			'data'      => 'root1',
			'default'   => 'root',
		],
		# Пароль доступа
		'PASS'         => [
			'set_title' => 'password',
			'data'      => '123456',
			'default'   => '',
		],
		# Имя БД
		'DBNAME'       => [
			'set_title' => 'db',
			'data'      => 'db_name',
			'default'   => null,
		],
		# Кодировка (mysql)
		'ENCODING'     => [
			'set_title' => 'encoding',
			'data'      => 'utf81',
			'default'   => 'utf8',
		],
		# Кодировка Системы (mssql)
		'ENCODING_SYS' => [
			'set_title' => 'encoding_sys',
			'data'      => 'UTF-81',
			'default'   => 'UTF-8',
		],
		# Кодировка БД (mssql)
		'ENCODING_DB'  => [
			'set_title' => 'encoding_db',
			'data'      => 'CP12511',
			'default'   => 'CP1251',
		],
	];
	/** Тестируемый объект */
	private $_test_object = null;



	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {
		$class_name = $this->class_name_test;
		$this->_test_object = new $class_name();
		$this->_test_object->set_setting('TEST_DATA', $this->obj_get('TEST_DATA'));
	}



	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}



	/** Генератор заглушки элемента */
	public function obj_get($prefix) {
		$class_name = $this->class_name_get;
		$obj = new $class_name();
		foreach ($this->arr_setting_data as $k => $v) {
			$obj->set("{$prefix}_{$k}",   $v['data']);
		}
		return $obj;
	}



	/** Проверяет получение ссылки на файл драйвера */
	public function test_get_driver_file() {
		$result = $this->_test_object->get_driver_file();
		# Это условие необходимо для правильности последующей проверки
		$this->assertNotFalse(realpath($result), 'Файл не cуществует: ' . $result);
		$this->assertEquals(realpath($result), realpath(__DIR__ . '/../src/drivers/empty_class.php'), 'Ссылки на файл класса не совпадают');
	}



	/** Проверяет получение имени класса драйвера */
	public function test_get_driver_class() {
		$result = $this->_test_object->get_driver_class();
		$this->assertEquals($result, '\RusaDrako\driver_db\drivers\empty_class', 'Кол-во элементов не совпадает');
	}



	/** Проверяет значения настроек */
	public function test_get_value() {
		$prefix = 'TEST_DATA';
		foreach ($this->arr_setting_data as $k => $v) {
			$result = $this->_test_object->get_value($v['set_title']);
			$this->assertEquals($result, $v['data'], "Значение элемента не совпадает: {$k} -> {$v['title']}");
		}
	}



	/** Проверяет значения по умолчанию */
	public function test_def_setting() {
		# Формируем объект без нужных настроек
		$class_name = $this->class_name_test;
		$this->_test_object = new $class_name();
		$this->_test_object->set_setting('TEST_DATA', $this->obj_get('TEST_DATA_2'));

		$prefix = 'TEST_DATA';
		foreach ($this->arr_setting_data as $k => $v) {
			$result = $this->_test_object->get_value($v['set_title']);
			$this->assertEquals($result, $v['default'], "Значение элемента не совпадает: {$k} -> {$v['title']}");
		}
	}



/**/
}
