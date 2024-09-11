<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/autoload.php');





/**
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class db_Test extends TestCase {
	/** */
	private $class_name_data = 'RusaDrako\driver_db\db';
	/** Тестируемый объект */
	private $_test_object = null;
	/** Объект-заглушка для БД */



	/** Вызывается перед каждым запуском тестового метода */
	protected function setUp() : void {
		$class_name = $this->class_name_data;
		$this->_test_object = new $class_name('test');
		$this->_test_object->set_driver($this->mock_driver());
	}



	/** Вызывается после каждого запуска тестового метода */
	protected function tearDown() : void {}



	/** Генератор заглушки элемента */
	public function mock_driver() {
		$this->_test_data_mock = $this->createMock(\RusaDrako\driver_db\drivers\null_class::class);
		return $this->_test_data_mock;
	}



	/** Проверяет загрузки разных классов */
	public function test_many_class() {
		$class_name = $this->class_name_data;
		$obj_1 = $class_name::call('test');
		$obj_2 = $class_name::call('test_1');
		$obj_3 = $class_name::call('test');

		$this->assertTrue($obj_1 == $obj_2, 'Объекты не однотипны');
		$this->assertFalse($obj_1 === $obj_2, 'Один и тот же объект');
		$this->assertTrue($obj_1 == $obj_3, 'Объекты не однотипны');
		$this->assertTrue($obj_1 === $obj_3, 'Разные объекты для сравнения');
	}



	/** Проверяет получение объекта драйвера */
	public function test_set_driver_object() {
		$reg = new \RusaDrako\driver_db\db_get();
		$reg->set('TEST_DRIVER', 'null');
		$this->_test_object->set_setting_object('TEST', $reg);

		$result = $this->_test_object->set_driver_object();

		$this->assertTrue(is_object($result), 'Не является объектом');

		$this->assertTrue(is_a($result, 'RusaDrako\driver_db\drivers\_interface_class'), 'Не совпадает тип объекта');
	}



	/** Проверяет получение объекта драйвера (ошибка типа) */
	public function test_set_driver_object_error() {
		# Нет настроек
		$result = false;
		try {
			$this->_test_object->set_driver_object();
		} catch (\Exception $e) {
			$result = $e->getMessage();
		}
		$this->assertEquals($result, 'Отсутствуют настройки подключения к БД', 'Неверное сообщение об ишибке');

		# Нет файла
		$reg = new \RusaDrako\driver_db\db_get();
		$reg->set('TEST_DRIVER', 'empty1');
		$this->_test_object->set_setting_object('TEST', $reg);

		$result = false;
		try {
			$this->_test_object->set_driver_object();
		} catch (\Exception $e) {
			$result = $e->getMessage();
		}
		$str_err = "Не удалось подключить драйвер БД: Файл драйвера не найден:";
		$this->assertEquals(substr($result, 0, strlen($str_err)), $str_err, 'Неверное сообщение об ишибке');

		# Нет класса
		$reg->set('TEST_DRIVER', 'empty');
		$this->_test_object->set_setting_object('TEST', $reg);

		$result = false;
		try {
			$this->_test_object->set_driver_object();
		} catch (\Exception $e) {
			$result = $e->getMessage();
		}
		$str_err = "Не удалось подключить драйвер БД: Класс драйвера не найден:";
		$this->assertEquals(substr($result, 0, strlen($str_err)), $str_err, 'Неверное сообщение об ишибке');
	}



	/** Проверяет загрузки разных классов (ошибка типа) */
	public function test_set_driver_error() {
		$this->expectException('TypeError');
		$this->_test_object->set_driver(null);
	}










	/** Проверяет выполнение query */
	public function test_query() {
		$sql = 'query ? ?';
		$data = ['data_1' => 123, 'data_2' => 456];
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('query')
			->with(
				$this->equalTo($sql),
				$this->equalTo($data)
			)
			->willReturn($return);

		$result = $this->_test_object->query($sql, $data);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение query (без передачи данных) */
	public function test_query_not_data() {
		$sql = 'query ? ?';
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('query')
			->with(
				$this->equalTo($sql),
				$this->equalTo([])
			)
			->willReturn($return);

		$result = $this->_test_object->query($sql);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение query (ошибка типа) */
	public function test_query_error() {
		$this->expectException('TypeError');
		$this->_test_object->query([]);
	}



	/** Проверяет выполнение query (ошибка типа) */
	public function test_query_error_2() {
		$this->expectException('TypeError');
		$this->_test_object->query('', '');
	}



	/** Проверяет выполнение insert */
	public function test_insert() {
		$table = 'table_1';
		$data = ['data_1' => 123, 'data_2' => 456];
		$where = ['where_1', 'where_2'];
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('insert')
			->with(
				$this->equalTo($table),
				$this->equalTo($data),
				$this->equalTo($where)
			)
			->willReturn($return);

		$result = $this->_test_object->insert($table, $data, $where);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение insert (без условия подстановки) */
	public function test_insert_not_where() {
		$table = 'table_1';
		$data = ['data_1' => 123, 'data_2' => 456];
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('insert')
			->with(
				$this->equalTo($table),
				$this->equalTo($data),
				$this->equalTo([])
			)
			->willReturn($return);

		$result = $this->_test_object->insert($table, $data);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение insert (ошибка типа) */
	public function test_insert_error() {
		$this->expectException('TypeError');
		$this->_test_object->insert([]);
	}



	/** Проверяет выполнение insert (ошибка типа) */
	public function test_insert_error_2() {
		$this->expectException('TypeError');
		$this->_test_object->insert('', '');
	}



	/** Проверяет выполнение select */
	public function test_select() {
		$table = 'table_1';
		$assoc = false;
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('select')
			->with(
				$this->equalTo($table),
				$this->equalTo($assoc)
			)
			->willReturn($return);

		$result = $this->_test_object->select($table, $assoc);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение select (не передают ассоциотивность)*/
	public function test_select_not_assoc() {
		$table = 'table_1';
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('select')
			->with(
				$this->equalTo($table),
				$this->equalTo(true)
			)
			->willReturn($return);

		$result = $this->_test_object->select($table);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение select (ошибка типа) */
	public function test_select_error() {
		$this->expectException('TypeError');
		$this->_test_object->select([]);
	}



	/* * Проверяет выполнение select (ошибка типа) * /
	public function test_select_error_2() {
		$this->expectException('TypeError');
		$this->_test_object->select('', []);
	}



	/** Проверяет выполнение update */
	public function test_update() {
		$table = 'table_1';
		$data = ['data_1' => 123, 'data_2' => 456];
		$where = ['where_1', 'where_2'];
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('update')
			->with(
				$this->equalTo($table),
				$this->equalTo($data),
				$this->equalTo($where)
			)
			->willReturn($return);

		$result = $this->_test_object->update($table, $data, $where);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение update (ошибка типа) */
	public function test_update_error() {
		$this->expectException('TypeError');
		$this->_test_object->update([]);
	}



	/** Проверяет выполнение update (ошибка типа) */
	public function test_update_error_2() {
		$this->expectException('TypeError');
		$this->_test_object->update('', '');
	}



	/** Проверяет выполнение delete */
	public function test_delete() {
		$table = 'table_1';
		$where = ['where_1', 'where_2'];
		$return = 'request';

		# Драйвер БД
		$this->_test_data_mock->expects($this->exactly(1))
			->method('delete')
			->with(
				$this->equalTo($table),
				$this->equalTo($where)
			)
			->willReturn($return);

		$result = $this->_test_object->delete($table, $where);
		$this->assertEquals($result, $return, 'Неверное значение возвращаемого элемента');
	}



	/** Проверяет выполнение delete (ошибка типа) */
	public function test_delete_error() {
		$this->expectException('TypeError');
		$this->_test_object->delete([]);
	}



/**/
}
