<?php

use PHPUnit\Framework\TestCase;
use SQLBuild\Set;
use SQLBuild\SQLBuild;
use SQLBuild\SQLOperator;
use SQLBuild\Where;

class UpdateQueryTest extends TestCase
{
    public function testUpdates()
    {
        try {
            $query = (new SQLBuild())
                ->addTable('users', 'admins')
                ->addSet(new Set("password=newPassword=lol"))
                ->addWhere(new Where('id=25', SQLOperator::AND), new Where('password=simpleP=assword'))
                ->addSort(['id'], SQLOperator::DESC)
                ->getUpdate();
            var_dump($query);
            $this->assertTrue(true);
        }catch (Exception $e) {
            print $e->getFile() . PHP_EOL;
            print $e->getLine() . PHP_EOL;
            print $e->getMessage();
            $this->assertTrue(false);
        }
    }
}