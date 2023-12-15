<pre><?php

use RusaDrako\driver_db\DB;

require_once('../src/autoload.php');

$db = new DB();

$set_1 = [
	'DRIVER' => DB::DRV_MYSQLI,
	'HOST' => 'localhost',
	'USER' => 'root',
	'DBNAME' => '',
];

$set_2 = [
	'DRIVER' => DB::DRV_MYSQL_PDO,
	'HOST' => 'localhost',
	'USER' => 'root',
	'DBNAME' => '',
];

# Настройки подключения
$db->setDB('db1', $set_1);
$db->setDB('db2', $set_2);

print_r($db);

echo '<hr>';
echo '<hr>';

$sql = 'SELECT @@version';

$db_1 = $db->getDBConnect('db1');
$result_1 = $db_1->select($sql);
print_r($result_1);
echo '<hr>';
echo '<hr>';

$db_2 = $db->getDBConnect('db2');
$result_2 = $db_2->select($sql);
print_r($result_2);
echo '<hr>';
echo '<hr>';
