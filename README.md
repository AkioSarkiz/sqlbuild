# SQLBuild

#### Install:
```
$ composer require akiosarkiz/sqlbuild
```

#### Example sql query 1:
##### create Select query
```php
$sqlBuild = new SQLBuild();

try {
    var_dump($sqlBuild
        ->addTable('users')
        ->addSort(['id', 'password'], SQLOperator::DESC)
        ->addWhere(new Where('command=passw\"ord', SQLOperator::AND), new Where('password=tempString'))
        ->getSelect()
    );
} catch (Exception $e) {
    print $e->getFile() . PHP_EOL;
    print $e->getLine() . PHP_EOL;
    print $e->getMessage();
}
// string(110) "SELECT * FROM `users` WHERE `command`="passw\\\"ord" AND `password`="tempString" ORDER BY `id`,`password` DECS"
```

#### Example sql query 2:
##### create Insert query
```php
$sqlBuild = new SQLBuild();

try {
    var_dump( $sqlBuild
        ->addTable('users', 'admins')
        ->addValue(new Value('passw"'))
        ->addSort(['id'], SQLOperator::DESC)
        ->getInsert()
    );
} catch (Exception $e) {
    print $e->getFile() . PHP_EOL;
    print $e->getLine() . PHP_EOL;
    print $e->getMessage();
}
// string(65) "INSERT INTO `users`,`admins` VALUES (passw\") ORDER BY `id` DECS;"
```

#### Example sql query 3:
##### create Update query
```php
$sqlBuild = new SQLBuild();

try {
    var_dump( $sqlBuild
        ->addTable('users', 'admins')
        ->addSet(new Set('password=newPassword'))
        ->addWhere(new Where('id=25'), new Where('password=simplePassword'))
        ->addSort(['id'], SQLOperator::DESC)
        ->getUpdate()
    );
} catch (Exception $e) {
    print $e->getFile() . PHP_EOL;
    print $e->getLine() . PHP_EOL;
    print $e->getMessage();
}
// string(114) "UPDATE `users`,`admins` SET `password`="newPassword" WHERE `id`=25 `password`="simplePassword" ORDER BY `id` DECS;"
```