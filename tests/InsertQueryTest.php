<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use SQLBuild\SQLBuild;
use SQLBuild\Value;

class InsertQueryTest extends TestCase
{
    public final function test1(): Void
    {
        try {
            $query = (new SQLBuild)
                ->addTable('page')
                ->addColumn('title', 'tags', 'content')
                ->addValue(
                    new Value('PageTitle'),
                    new Value('tag;tag;tag'),
                    new Value('Content = 12312<html lang="ru"><?php echo 123; ?></html>>')
                )->getInsert();
            var_dump($query);
            $this->assertTrue(true);
        } catch (Exception $e) {
            print $e->getFile() . PHP_EOL;
            print $e->getLine() . PHP_EOL;
            print $e->getMessage();
            $this->assertTrue(false);
        }
    }

    public final function test2(): Void
    {
        try {
            $query = (new SQLBuild)
                ->addTable('page')
                ->addColumn('id', 'tags', 'content')
                ->addValue(
                    new Value(123456),
                    new Value('tag;tag;tag'),
                    new Value('12312<html lang="ru"><?php echo 123; ?></html>>')
                )->getInsert();
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