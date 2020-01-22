<?php declare(strict_types=1);


use SQLBuild\SQLBuild;
use SQLBuild\SQLOperator;
use SQLBuild\Where;


require __DIR__ . '/../vendor/autoload.php';


# -----------------------------------------------------------------------------------------------------------------

$sqlBuild = new SQLBuild();

try {
    $query = $sqlBuild
        ->addSelect([])
        ->addTable(['users', 'admin', 'guest'])
        ->addWhere(
            new Where('last_visit=21.01.2019', SQLOperator:: OR),
            new Where('data_register=21.01.2019'))
        ->getSelect();

    var_dump($query);
    // string(127) "SELECT `password`,`email`,`login` FROM `users`,`admin`,`guest` WHERE `last_visit`="21.01.2019" OR `data_register`="21.01.2019" "

} catch (Exception $e) { echo $e->getMessage(); }