<?php

namespace RusaDrako\driver_db;

require_once('db.php');
require_once('db_get.php');

require_once(__DIR__ . '/drivers/_interface_class.php');

require_once(__DIR__ . '/drivers/_trait__delete.php');
require_once(__DIR__ . '/drivers/_trait__error.php');
require_once(__DIR__ . '/drivers/_trait__get_set.php');
require_once(__DIR__ . '/drivers/_trait__insert.php');
require_once(__DIR__ . '/drivers/_trait__query.php');
require_once(__DIR__ . '/drivers/_trait__update.php');

require_once(__DIR__ . '/drivers/mysql_class.php');
require_once(__DIR__ . '/drivers/mysql_class.php');
require_once(__DIR__ . '/drivers/mysql_pdo_class.php');
require_once(__DIR__ . '/drivers/sqlsrv_class.php');
require_once(__DIR__ . '/drivers/sqlsrv_pdo_class.php');

require_once('aliases.php');
