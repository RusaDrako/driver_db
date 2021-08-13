<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/autoload.php');





/**
 * @author Петухов Леонид <l.petuhov@okonti.ru>
 */
class db_get_Test extends TestCase {
	/** */
	private $class_name_data = 'RusaDrako\driver_db\db_get';
	/** Тестируемый объект */
	private $_test_object = null;
	/** Объект-заглушка для БД */



	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {
		$class_name = $this->class_name_data;
		$this->_test_object = new $class_name();
	}



	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}



	/** Проверяет получение данных по ключу */
	public function test_set_get() {
		$key         = 'test';
		$value       = 'test_value_1';
		$value_2     = 'test_value_2';
		$value_def   = 'test_value_def';

		$this->assertNull($this->_test_object->get($key), 'Неверное значение возвращаемого элемента');
		$this->assertEquals($this->_test_object->get($key, $value_def), $value_def, 'Неверное значение возвращаемого элемента');

		$this->_test_object->set($key, $value);
		$this->assertEquals($this->_test_object->get($key, $value_def), $value, 'Неверное значение возвращаемого элемента');

		$this->_test_object->set($key, $value_2);
		$this->assertEquals($this->_test_object->get($key, $value_def), $value_2, 'Неверное значение возвращаемого элемента');
	}



/**/
}
