<?php declare(strict_types=1);


namespace Tests;


use PHPUnit\Framework\TestCase;
use SQLBuild\SQLBuild;
use SQLBuild\SQLException;
use SQLBuild\SQLOperator;
use SQLBuild\Where;


class DeleteQueryTest extends TestCase
{
    public function test1()
    {
        try {
            $query = (new SQLBuild)
                ->addTable('page')
                ->addWhere(
                    new Where('page=123', SQLOperator::AND),
                    new Where('test=test')
                )->getDelete();
            var_dump($query);
            $this->assertTrue(true);
        } catch (SQLException $e) {
            print $e->getFile() . PHP_EOL;
            print $e->getLine() . PHP_EOL;
            print $e->getMessageLang(SQLException::LANG['ru']);
            $this->assertTrue(false);
        }
    }
}
