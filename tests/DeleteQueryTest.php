<?php


use PHPUnit\Framework\TestCase;
use SQLBuild\SQLBuild;
use SQLBuild\Value;
use SQLBuild\Where;

class DeleteQueryTest extends TestCase
{
    public function test1()
    {
        try {
            $query = (new SQLBuild)
                ->addTable('page')
                ->addWhere(
                    new Where('page=123', \SQLBuild\SQLOperator::AND),
                    new Where('test=test')
                )->getDelete();
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
