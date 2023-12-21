# RusaDrako\\driver_db

[![Version](http://poser.pugx.org/rusadrako/driver_db/version)](https://packagist.org/packages/rusadrako/driver_db)
[![Total Downloads](http://poser.pugx.org/rusadrako/driver_db/downloads)](https://packagist.org/packages/rusadrako/driver_db/stats)
[![License](http://poser.pugx.org/rusadrako/driver_db/license)](./LICENSE)

Адаптер для работы с подключениями к различным БД.

## Установка (composer)
```sh
composer require 'rusadrako/driver_db'
```


## Установка (manual)
- Скачать и распоковать библиотеку.
- Добавить в код инструкцию:
```php
require_once('/driver_db/src/autoload.php')
```


## Пример выполнения запроса
```PHP
use RusaDrako\driver_db\DB;
$db = new DB();
// Настройки подключения к БД
$db_set = [
	'DRIVER' => DB::DRV_MYSQLI,
	'HOST' => 'localhost',
	'USER' => 'root',
	'PASS' => '',
	'DBNAME' => 'test',
];
// Установка настроек подключения
$db->setDB('db_name', $db_set);
// Активация подключения
/** @var RusaDrako\driver_db\drivers\_abs_driver $db_connect */
$db_connect = $db->getDBConnect('db_name');
// Выполнение запроса
/** @var array $result */
$result = $db_connect->select('SELECT @@version');
var_dump($result);
```

## Поддерживаемые библиотеки
```PHP
use RusaDrako\driver_db\DB;

DB::DRV_MYSQL;       // mysql
DB::DRV_MYSQLI;      // mysqli
DB::DRV_MYSQL_PDO;   // PDO:mysql
DB::DRV_SQLITE3;     // SQLite3
DB::DRV_SQLITE3_PDO; // PDO:sqlite
DB::DRV_SQLSRV;      // sqlsrv
DB::DRV_SQLSRV_PDO;  // PDO:sqlsrv
DB::DRV_PG_PDO;      // PDO:pgsql
```

## Матрица настроек подключения
| Поле | Описание | По умолчанию | mysql | mysqli | PDO:mysql | SQLite3 | PDO:sqlite | sqlsrv | PDO:sqlsrv | PDO:pgsql |
| :--- | :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **DRIVER** | Драйвер | DB::DRV_MYSQLI | DB::DRV_MYSQL | DB::DRV_MYSQLI | DB::DRV_MYSQL_PDO | DB::DRV_SQLITE3 | DB::DRV_SQLITE3_PDO | DB::DRV_SQLSRV | DB::DRV_SQLSRV_PDO | DB::DRV_PG_PDO |
| **HOST** | Сервер | localhost | + | + | + | + | + | + | + | + |
| **PORT** | Порт | NULL | + | + | + | | | + | + | + |
| **USER** | Пользователь | root | + | + | + | | | + | + | + |
| **PASS** | Пароль | NULL | + | + | + | | | + | + | + |
| **DBNAME** | Имя БД | NULL | + | + | + | | | + | + | + |
| **ENCODING** | Кодировка | utf8 | + | + | + | | | | | |
| **ENCODING_SYS** | Кодировка Системы | UTF-8 | | | | | | + | + | |
| **ENCODING_DB** | Кодировка БД | CP1251 | | | | | | + | | |



## Методы запросов
```php
/**
 * Cоздаёт строку в таблице с заданными переменными.
 * @param string $table_name Имя таблицы.
 * @param array $arr_insert Массив с переменными для добавления.
 * @param array $arr_where Условие добавления строки.
 * @return array Ответ БД: ID номер новой строки или false.
 */
public function insert($table_name, $arr_insert, $arr_where = []) { ... }
```

```PHP
/**
 * Возвращает ID последней вставленной строки или значение последовательности
 */
public function insert_id() { ... }
```

```PHP
/**
 * Функция возвращает возвращает результат запроса в БД.
 * @param string $query Строка запроса.
 * @param bool $return_error Маркер возврата сообщения об ошибке.
 * @return array Ответ БД.
 */
public function query($query) { ... }
```

```PHP
/**
 * Возвращает массив результата запроса select (массив полей ID) или false.
 * @param string $query Строка запроса.
 * @param bool $assoc Возвращать ассоциотивный массив полей
 * @return array Ответ БД (массив данных).
 */
public function select($query, $assoc = true) { ... }
```

```PHP
/**
 * Обновляет строку в таблице масивом переменных по условию.
 * @param string $table_name Имя таблицы.
 * @param array $arr_update Массив с переменными для обновления.
 * @param array $arr_where Условие обработки строк.
 * @return bool Ответ БД: true или false.
 */
public function update($table_name, $arr_update, $arr_where) { ... }
```

```PHP
/**
 * Удаляет строку из таблицы по условию.
 * @param string $table_name Имя таблицы.
 * @param array $arr_where Условие обработки строк.
 * @return bool Ответ БД: true - выполнено; false - не выполнено.
 */
public function delete($table_name, $arr_where) { ... }
```
