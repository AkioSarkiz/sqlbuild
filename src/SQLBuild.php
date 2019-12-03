<?php

declare(strict_types=1);


namespace SQLBuild;


use Exception;


/**
 * Class SQLBuild создан для упращение создания SQL запросов.
 *
 * На первый взгяд может показаться, что это бесполезно.
 * Но если вы хотите быть уверены в своих запросах, чтоб не контролировать каждую скобку,
 * типы, ограничения, валидность, то этот класс вас спасёт.
 *
 * @author Akio Sarkiz
 * @example examples/GenerateSQLExample.php
 * @package Lib
 */
final class SQLBuild
{
    /** @var bool авто чистка параметров после получения getSelect, getUpdate etc */
    private $autoClear = true;

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
    /** @var CollectionLimit */
    private $limitCollection;
    /** @var GroupByCollection */
    private $groupByCollection;

    /**
     * Освобождение памяти
     * Очищается автоматически при получении запроса
     * Можно задать поведение с помощью { @see setAutoClear }
     *
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
     * Для INSERT
     * Колонки для значений
     *
     * @param String ...$arr
     * @return SQLBuild
     */
    public function addColumn(String ...$arr): SQLBuild
    {
        $this->columnCollection = new ColumnCollection($arr);
        return $this;
    }

    /**
     * Для SELECT
     * Лимит на выборку
     *
     * @param $max
     * @param null $start
     * @return SQLBuild
     */
    public function addLimit($max, $start = null): SQLBuild
    {
        $this->limitCollection = new CollectionLimit($max, $start);
        return $this;
    }

    /**
     * Для SELECT
     * Убирает повторения в выбраных колонах
     *
     * @param $input
     * @return SQLBuild
     */
    public function addGroupBy($input): SQLBuild
    {
        $this->groupByCollection = new GroupByCollection($input);
        return $this;
    }

    /**
     * Для INSERT
     * Значения для добавления в таблицу
     *
     * @param Value ...$obj
     * @return SQLBuild
     */
    public function addValue(Value ...$obj): SQLBuild
    {
        $this->valueCollection = new ValueCollection($obj);
        return $this;
    }

    /**
     * Для SELECT|INSERT|UPDATE
     * Таблиц(ы) в которых нужно проводить манипуляции
     *
     * @param String[] $objs
     * @return SQLBuild
     */
    public function addTable(String ...$objs): SQLBuild
    {
        $this->tableCollection = new TableCollection($objs);
        return $this;
    }

    /**
     * Для SELECT
     * Выбор даннных, если не указан, то выьираются все
     *
     * @param String[] $obj
     * @return SQLBuild
     */
    public function addSelect(String ...$obj): SQLBuild
    {
        $this->selectCollection = new SelectCollection($obj);
        return $this;
    }

    /**
     * Для SELECT|UPDATE
     * Добавляет критерии к отбору
     *
     * @param Where[] $objs
     * @return SQLBuild
     */
    public function addWhere(Where ...$objs): SQLBuild
    {
        $this->whereCollection = new WhereCollection($objs);
        return $this;
    }

    /**
     * Добавляет Set'ры для Insert query
     *
     * @param Set ...$objs
     * @return SQLBuild
     */
    public function addSet(Set ...$objs): SQLBuild
    {
        $this->setCollection = new SetCollection($objs);
        return $this;
    }

    /**
     * Для SELECT
     * Добавляет сортировку
     * SQLOperator::DESC - убывание
     * SQLOperator::ASC - возрастание
     *
     * @param array $strings
     * @param int $sort
     * @return SQLBuild
     */
    public function addSort(array $strings, int $sort = SQLOperator::DESC): SQLBuild
    {
        $this->sortCollection = new SortCollection($strings, $sort);
        return $this;
    }

    /**
     * Получение Select запроса MySQL
     *
     * @return String
     * @throws Exception
     */
    public function getSelect(): String
    {
        try {
            $renderSelect = ($this->selectCollection) ? $this->selectCollection->render() : '*';
            $renderTable = ($this->tableCollection) ? $this->tableCollection->render() : '';
            $renderWhere = ($this->whereCollection)? $this->whereCollection->render() : '';
            $renderSort = ($this->sortCollection) ? $this->sortCollection->render() : '';
            $renderLimit = ($this->limitCollection) ? $this->limitCollection->render() : '';
            $renderGroupBy = ($this->groupByCollection) ? $this->groupByCollection->render() : '';

            if ($renderTable == '')
                throw new Exception('please, add table');

            return sprintf(
                'SELECT %s FROM %s %s %s %s %s',
                $renderSelect, $renderTable, $renderWhere, $renderGroupBy, $renderSort, $renderLimit
            );
        } catch (Exception $e) {
            throw $e;
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finaly потому что нам
            // нужны значения до отдачи
            if ($this->autoClear) $this->free();
        }
    }

    /**
     * Получение Insert запроса MySQL
     *
     * @return String
     * @throws Exception
     */
    public function getInsert(): String
    {
        try {
            $renderTable = ($this->tableCollection) ? $this->tableCollection->render() : '';
            $renderColumn = ($this->columnCollection) ? $this->columnCollection->render() : '';
            $renderValue = ($this->valueCollection) ? $this->valueCollection->render() : '';

            if ($renderTable == '')
                throw new Exception('please, add table');
            elseif ($this->tableCollection->count() > 1)
                throw new Exception('max tables = 1, your collection have ' . $this->tableCollection->count() . ' tables');
            elseif ($renderValue == '')
                throw new Exception('please, add values');

            return sprintf(
                'INSERT INTO %s%s VALUES (%s);',
                $renderTable, $renderColumn, $renderValue
            );
        } catch (Exception $e) {
            throw $e;
        }finally {
            // Проверка на чистку таблиц.
            // Сделано в finaly потому что нам
            // нужны значения до отдачи
            if ($this->autoClear) $this->free();
        }
    }

    /**
     * Получение Update запроса MySQL
     *
     * @return String
     * @throws Exception
     */
    public function getUpdate(): String
    {
        $renderTable = ($this->tableCollection) ? $this->tableCollection->render() : '';
        $renderSet = ($this->setCollection) ? $this->setCollection->render() : '';
        $renderWhere = ($this->whereCollection) ? $this->whereCollection->render() : '';

        if ($renderTable == '')
            throw new Exception('please, add table');
        elseif ($renderSet == '')
            throw new Exception('please, add set');

        try {
            return sprintf(
                'UPDATE %s %s %s;',
                $renderTable, $renderSet, $renderWhere
            );
        } catch (Exception $e) {
            exit('error!');
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finaly потому что нам
            // нужны значения до отдачи
            if ($this->autoClear) $this->free();
        }
    }

    /**
     * Возвращает запроса удаления уведомления
     *
     * @return String
     * @throws Exception
     */
    public function getDelete(): String
    {
        $renderTable = ($this->tableCollection) ? $this->tableCollection->render() : '';
        $renderWhere = ($this->whereCollection) ? $this->whereCollection->render() : '';

        if ($renderTable == '')
            throw new Exception('please, add table');
        elseif ($renderWhere == '')
            throw new Exception('please, add where');

        try {
            return sprintf(
                'DELETE FROM %s %s;',
                $renderTable, $renderWhere
            );
        } catch (Exception $e) {
            throw $e;
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finaly потому что нам
            // нужны значения до отдачи
            if ($this->autoClear) $this->free();
        }
    }

    /**
     * Очищать ли автоматически память после
     * каждого getSelect|getInsert etc запроса к классу
     *
     * @param bool $autoClear
     * @return SQLBuild
     */
    public function setAutoClear(bool $autoClear): SQLBuild
    {
        $this->autoClear = $autoClear;
        return $this;
    }

    public function __destruct()
    {
        unset($selectCollection);
        unset($whereCollection);
        unset($setCollection);
        unset($columnCollection);
        unset($valueCollection);
        unset($tableCollection);
        unset($sortCollection);
        unset($limitCollection);
        unset($groupByCollection);
    }
}