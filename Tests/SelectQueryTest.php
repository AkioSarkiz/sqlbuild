<?php declare(strict_types=1);


namespace Tests;


use PHPUnit\Framework\TestCase;
use SQLBuild\SQLBuild;
use SQLBuild\SQLOperator;
use SQLBuild\SQLType;
use SQLBuild\Where;


class SelectQueryTest extends TestCase
{
    public function test1(): Void
    {
        try {
            $query = (new SQLBuild())
                ->addTable('users')
                ->addSort(['id', 'password'], SQLOperator::DESC)
                ->addWhere(
                    new Where('command=pa==!>ssw\"ord', SQLOperator:: AND),
                    (new Where('password=tempString', SQLOperator::AND))->addLike(),
                    new Where('command=pa==!>ssw\"ord', SQLOperator:: NONE)
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

    public function test2(): Void
    {
        try {
            $query = (new SQLBuild())
                ->addTable('communities')
                ->addSort(['id'], SQLOperator::DESC)
                ->addJoin(SQLOperator::INNER, 'communities_users')
                ->addLimit(500)
                ->addGroupBy(['title', 'id'])
                ->addWhere(
                    new Where('true=true', SQLOperator:: AND, SQLType::BOOL),
                    new Where('false=false', SQLOperator::NONE, SQLType::BOOL)
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