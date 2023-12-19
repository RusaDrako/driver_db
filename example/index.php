<pre><?php

use RusaDrako\driver_db\DB;
use RusaDrako\driver_db\drivers\DriverDB;

require_once('../src/autoload.php');

$db = new DB();

$arr_db_set['mysql'] = [
	'DRIVER' => DB::DRV_MYSQLI,
	'HOST' => 'localhost',
	'USER' => 'root',
	'PASS' => '',
	'DBNAME' => '',
];

$arr_db_set['mysql_pdo'] = array_merge($arr_db_set['mysql'], ['DRIVER' => DB::DRV_MYSQL_PDO]);

$arr_db_set['pg_pdo'] = [
	'DRIVER' => DB::DRV_PG_PDO,
	'HOST' => 'localhost',
	'USER' => 'postgres',
	'PASS' => 'postgres',
//	'DBNAME' => '',
];

$arr_db_set['sqlite3'] = [
	'DRIVER' => DB::DRV_SQLITE3,
	'HOST' => __DIR__ . '/test.db',
];

$arr_db_set['sqlite3_pdo'] = array_merge($arr_db_set['sqlite3'], ['DRIVER' => DB::DRV_SQLITE3_PDO]);

# Настройки подключения
foreach($arr_db_set as $k => $v) {
	$db->setDB($k, $v);
}

//print_r($db);

echo '<hr>';
echo '<hr>';

# Версия БД
foreach($arr_db_set as $k => $v) {
	$sql = '';
	if (in_array($k, ['mysql', 'mysql_pdo'])) {
		$sql = 'SELECT @@version';
	} else if (in_array($k, ['pg', 'pg_pdo'])) {
		$sql = 'SELECT version()';
	} else if (in_array($k, ['sqlite3', 'sqlite3_pdo'])) {
		$sql = 'SELECT sqlite_version()';
	}
	$db_connect = $db->getDBConnect($k);
	$result = $db_connect->select($sql);
	print_r($result);
	echo '<hr>';
	echo '<hr>';
}



$sql = 'SELECT wrongcolumn FROM wrongtable';

# Запрос с ошибкой
foreach($arr_db_set as $k => $v) {
	try {
		$db_connect = $db->getDBConnect($k);
		$result = $db_connect->select($sql);
		print_r($result);
	} catch(DriverDB $e) {
		echo '<hr>';
		$class = get_class($e);
		echo "<h3>{$k} => {$class}</h3>";
		echo $e->getMessage();
//		print_r($e);
	} catch(Exception $e) {
		echo '<hr>';
		$class = get_class($e);
		echo "<h3>{$k} => {$class}</h3>";
		echo $e->getMessage();

	}
	echo '<hr>';
	echo '<hr>';
}
