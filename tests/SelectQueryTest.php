<?php

use PHPUnit\Framework\TestCase;
use SQLBuild\SQLBuild;
use SQLBuild\SQLOperator;
use SQLBuild\Where;

class SelectQueryTest extends TestCase
{
    public final function test1(): Void
    {
        try {
            $query = (new SQLBuild())
                ->addTable('users')
                ->addSort(['id', 'password'], SQLOperator::DESC)
                ->addWhere(
                    new Where('command=pa==!>ssw\"ord', SQLOperator:: AND),
                    new Where('password=tempString')
                )
                ->getSelect();
            var_dump($query);
            $this->assertTrue(true);
        } catch (Exception $e) {
            print $e->getFile() . PHP_EOL;
            print $e->getLine() . PHP_EOL;
            print $e->getMessage();
            $this->assertTrue(false);
        }
    }
}