<?php

use SQLBuild\SelectCollection;
use SQLBuild\SQLBuild;
use SQLBuild\SQLOperator;
use SQLBuild\SQLType;
use SQLBuild\Where;

require __DIR__ . '/../vendor/autoload.php';

# -----------------------------------------------------------------------------------------------------------------

$sqlBuild = new SQLBuild();

try {
    $query = $sqlBuild
        ->addSelect([]) # OR ->addSelect(SelectCollection::ALL) OR empty      => ALL
        ->addTable('users')
        ->addWhere(
            new Where('last_visit=new str"', SQLOperator:: OR, SQLType::ARG, SQLType::STRING),
            new Where('data_register=true', SQLOperator::NONE, SQLType::ARG, SQLType::BOOL))
        ->addSort(['id', 'role'], SQLOperator::ASC)
        ->getSelect();

    var_dump($query);
    // string(123) "SELECT * FROM `users`,`admin`,`guest` WHERE `last_visit`="new str" OR `data_register`="21.01.2019" ORDER BY `id`,`role` ASC"

} catch (Exception $e) {echo $e->getMessage(); }

#-----------------
# OR
#-----------------

try {
    $query = $sqlBuild
        ->addTable('users')
        ->addWhere(
            new Where('last_visit=new str"', SQLOperator:: OR),
            new Where('data_register=true'))
        ->addSort(['id', 'role'], SQLOperator::ASC)
        ->getSelect();

    var_dump($query);
    // string(123) "SELECT * FROM `users`,`admin`,`guest` WHERE `last_visit`="new str" OR `data_register`="21.01.2019" ORDER BY `id`,`role` ASC"

} catch (Exception $e) { echo $e->getMessage(); }