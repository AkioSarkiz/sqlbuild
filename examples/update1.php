<?php declare(strict_types=1);


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
        ->addSet(new Set('password=newPassword'))
        ->addWhere(new Where('password=oldPassword'))
        ->getUpdate();


    var_dump($query);
    // string(75) "UPDATE `users` SET `password`="newPassword" WHERE `password`="oldPassword";"

}catch (Exception $e) { echo $e->getMessage(); }