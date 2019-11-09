<?php

declare(strict_types=1);


namespace SQLBuild;


use SQLBuild\ColumnCollection;
use SQLBuild\SelectCollection;
use SQLBuild\SetCollection;
use SQLBuild\TableCollection;
use SQLBuild\ValueCollection;
use SQLBuild\WhereCollection;

/**
 * Class [SQLGenerate || SQLG] создан для упращение создания SQL запросов.
 *
 * На первый взгяд может показаться, что это бесполезно.
 * Но если вы хотите быть уверены в своих запросах, чтоб не контролировать каждую скобку,
 * то этот класс вас спасёт.
 *
 * @author Akio Sarkiz
 * @example examples/GenerateSQLExample.php
 * @package Lib
 */
final class SQLBuild
{
    /** @var bool авто чистка параметров после получения getSelect, getUpdate etc */
    public $autoClear = true;

    /** @var SelectCollection */
    private $selectCollection;
    /** @var WhereCollection */
    private $whereCollection;
    /** @var SetCollection */
    private $setCollection;
    /** @var ColumnCollection */
    private $columnCollection;
    /** @var ValueCollection */
    private $valueCollection;
    /** @var TableCollection */
    private $tableCollection;
    /** @var SortCollection */
    private $sortCollection;

    /**
     * Освобождение памяти
     * @return SQLBuild
     */
    public function free(): SQLBuild
    {
        unset($this->selectCollection);
        unset($this->whereCollection );
        unset($this->arrFrom         );
        unset($this->updateCollection);
        unset($this->setCollection   );
        unset($this->columnCollection);
        unset($this->valueCollection );
        unset($this->tableCollection );
        return $this;
    }

    /**
     * Для SELECT|INSERT метода
     *
     * @param TableCollection $index
     * @return SQLBuild
     */
    public function addTable(String ...$objs): SQLBuild
    {
        $this->tableCollection = new TableCollection($objs);
        return $this;
    }

    /**
     * Для SELECT|INSERT метода
     *
     * @param \SQLBuild\SelectCollection $collection
     * @return SQLBuild
     */
    public function addSelect(String ...$obj): SQLBuild
    {
        $this->selectCollection = new SelectCollection($obj);
        return $this;
    }

    /**
     * Для SELECT|INSERT метода
     *
     * @param WhereCollection $index
     * @return SQLBuild
     */
    public function addWhere(Where ...$objs): SQLBuild
    {
        $this->whereCollection = new WhereCollection($objs);
        return $this;
    }

    /**
     * Добавляет сортировку
     * SQLOperator::ASC - убывание
     * SQLOperator::DESC - возрастание
     *
     * @param array $strings
     * @param int $sort
     * @return SQLBuild
     */
    public function addSort(array $strings, int $sort = SQLOperator::ASC): SQLBuild
    {
        $this->sortCollection = new SortCollection($strings, $sort);
        return $this;
    }

    public function getSelect(): String
    {
        try {
            return sprintf(
                'SELECT%sFROM%sWHERE%s%s',
                $this->selectCollection->render(),
                $this->tableCollection->render(),
                $this->whereCollection->render(),
                $this->selectCollection->render()
            );
        } catch (\Exception $e) {
            exit('error!');
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finaly потому что нам
            // нужны значения до отдачи
            if ($this->autoClear) $this->free();
        }
    }

    public function getInsert(): String
    {
        try {
            return sprintf(
                'INSERT INTO %s%s VALUES (%s)%s;',
                $this->tableCollection->render(),
                $this->columnCollection->render(),
                $this->valueCollection->render(),
                $this->selectCollection->render()
            );
        } catch (\Exception $e) {
            exit('error!');
        }finally {
            // Проверка на чистку таблиц.
            // Сделано в finaly потому что нам
            // нужны значения до отдачи
            if ($this->autoClear) $this->free();
        }
    }

    public function getUpdate(): String
    {
        try {
            return sprintf(
                'UPDATE%s SET%s WHERE%s%s;',
                $this->tableCollection ->render(),
                $this->setCollection   ->render(),
                $this->whereCollection ->render(),
                $this->selectCollection->render()
            );
        } catch (\Exception $e) {
            exit('error!');
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finaly потому что нам
            // нужны значения до отдачи
            if ($this->autoClear) $this->free();
        }
    }
}