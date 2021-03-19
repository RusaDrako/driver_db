<?php

if (class_exists('RD_DB', false)) {
    return;
}

$classMap = [
    'RusaDrako\\driver_db\\db' => 'RD_DB',
	'RusaDrako\\driver_db\\db_get' => 'RD_DB_get',
];

foreach ($classMap as $class => $alias) {
    class_alias($class, $alias);
}
