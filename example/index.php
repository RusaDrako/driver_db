<pre><?php
require_once('../src/autoload.php');

# Настройки подключения
$reg = new RD_DB_get();
$reg->set('TEST_DRIVER', 'mysqli');
$reg->set('TEST_HOST', 'localhost');
$reg->set('TEST_USER', 'root');
$reg->set('TEST_DBNAME', '');

$reg->set('TEST2_DRIVER', 'mysql_pdo');
$reg->set('TEST2_HOST', 'localhost');
$reg->set('TEST2_USER', 'root');
$reg->set('TEST2_DBNAME', '');

print_r($reg);




# Активация БД
$db = new RD_DB('TEST', $reg);
$db2 = new RD_DB('TEST2', $reg);

echo '<hr>';


$sql = 'SELECT @@version';


$result = $db->select($sql);
print_r($result);
echo '<hr>';


$result2 = $db2->select($sql);
print_r($result2);
echo '<hr>';
