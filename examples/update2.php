<?php

use SQLBuild\SQLBuild;
use SQLBuild\SQLOperator;
use SQLBuild\SQLType;
use SQLBuild\Where;
use SQLBuild\Set;
use SQLBuild\Value;

require __DIR__ . '/../vendor/autoload.php';

# -----------------------------------------------------------------------------------------------------------------

$sqlBuild = new SQLBuild();

try {
    $query = $sqlBuild
        ->addTable('users')
        ->addSet(new Set('password=newPassword', SQLType::AUTO, SQLType::AUTO))
        ->addWhere(
            new Where('password=oldPassword', SQLOperator::OR),
            new Where('id>25', SQLOperator::NONE, SQLType::STRING)
        )
        ->getUpdate();

    var_dump($query);
    // string(86) "UPDATE `users` SET `password`="newPassword" WHERE `password`="oldPassword" OR "id">25;"

}catch (Exception $e) { echo $e->getMessage(); }