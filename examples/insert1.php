<?php

use SQLBuild\SQLBuild;
use SQLBuild\SQLOperator;
use SQLBuild\Where;
use SQLBuild\Set;
use SQLBuild\Value;

require __DIR__ . '/../vendor/autoload.php';

# -----------------------------------------------------------------------------------------------------------------

$sqlBuild = new SQLBuild();

try {
    $query = $sqlBuild
        ->addTable('users')
        ->addColumn('login', 'password')
        ->addValue(new Value('originLogin'), new Value('originPassword'))
        ->getInsert();


    var_dump($query);
    // string(80) "INSERT INTO `users`(`login`,`password`) VALUES ("originLogin","originPassword");"

}catch (Exception $e) { echo $e->getMessage(); }