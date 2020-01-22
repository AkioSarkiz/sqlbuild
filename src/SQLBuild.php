<?php declare(strict_types=1);


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
    /** @var JoinCollection */
    public $joinCollection;

    /**
     * Освобождение памяти
     * Очищается автоматически при получении запроса
     * Можно задать поведение с помощью { @see setAutoClear }
     *
     * @return SQLBuild
     */
    public function free(): SQLBuild
    {
        $this->selectCollection = null;
        $this->whereCollection  = null;
        $this->arrFrom          = null;
        $this->updateCollection = null;
        $this->setCollection    = null;
        $this->columnCollection = null;
        $this->valueCollection  = null;
        $this->tableCollection  = null;
        $this->joinCollection   = null;
        return $this;
    }


    /**
     * Для INSERT
     * Колонки для значений
     *
     * @param String|array $args
     * @return SQLBuild
     * @throws Exception
     */
    public function addColumn($args): SQLBuild
    {
        if ($this->columnCollection === null)
            $this->columnCollection = new ColumnCollection();
        $this->columnCollection->add($args);
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
    public function addLimit(int $max, $start = null): SQLBuild
    {
        $this->limitCollection = new CollectionLimit($max, $start);
        return $this;
    }

    /**
     * Для SELECT
     * Объединение таблиц
     *
     * @param int $typeConst
     * @param String $nameTable
     * @return SQLBuild
     */
    public function addJoin(int $typeConst, String $nameTable): SQLBuild
    {
        $this->joinCollection = new JoinCollection($typeConst, $nameTable);
        return $this;
    }

    /**
     * Для SELECT
     * Убирает повторения в выбранных колонах
     *
     * @param String|array $input
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
     * @param String|array $args
     * @return SQLBuild
     */
    public function addTable($args): SQLBuild
    {
        if (is_null($this->tableCollection))
            $this->tableCollection = new TableCollection();
        $this->tableCollection->add($args);
        return $this;
    }

    /**
     * Для SELECT
     * Выбор даннных, если не указан, то выьираются все
     *
     * @param String|array $args
     * @return SQLBuild
     * @throws Exception
     */
    public function addSelect($args): SQLBuild
    {
        if (is_null($this->selectCollection))
            $this->selectCollection = new SelectCollection();
        try {
            $this->selectCollection->add($args);
        } catch (Exception $e) {
            throw $e;
        }
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
     * @param array|String $strings
     * @param int $sort
     * @return SQLBuild
     */
    public function addSort($strings, int $sort = SQLOperator::DESC): SQLBuild
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
            $renderJoin = ($this->joinCollection) ? $this->joinCollection->render() : '';

            if ($renderTable == '')
                throw new SQLException(SQLException::createLangArr(
                    'Use method "addTable()"',
                    'Используйте метод "addTable()"'
                ));

            return sprintf(
                'SELECT %s FROM %s%s%s%s%s%s;',
                $renderSelect,
                (($renderTable !== '') ? ' ' . $renderTable : ''),
                (($renderJoin !== '') ? ' ' . $renderJoin : ''),
                (($renderWhere !== '') ? ' ' . $renderWhere : ''),
                (($renderGroupBy !== '') ? ' ' . $renderGroupBy : ''),
                (($renderSort !== '') ? ' ' . $renderSort : ''),
                (($renderLimit !== '') ? ' ' . $renderLimit : '')
            );
        } catch (Exception $e) {
            throw $e;
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finally потому что нам
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
                throw new SQLException(SQLException::createLangArr(
                    'Use method "addTable()"',
                    'Используйте метод "addTable()"'
                ));
            elseif ($this->tableCollection->count() > 1)
                throw new SQLException(SQLException::createLangArr(
                    'Max tables == 1 for INSERT method, your collection have: ' . $this->tableCollection->count() . ' tables',
                    'max tables = 1, your collection have ' . $this->tableCollection->count() . ' tables'
                ));
            elseif ($renderValue == '')
                throw new SQLException(SQLException::createLangArr(
                    'Add values to object for SQL request',
                    'Добавьте значения к объекту для SQL запроса'
                ));

            return sprintf(
                'INSERT INTO %s%s VALUES (%s);',
                $renderTable, $renderColumn, $renderValue
            );
        } catch (Exception $e) {
            throw $e;
        }finally {
            // Проверка на чистку таблиц.
            // Сделано в finally потому что нам
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
            throw new SQLException(SQLException::createLangArr(
                'Use method "addTable()"',
                'Используйте метод "addTable()"'
            ));
        elseif ($renderSet == '')
            throw new SQLException(SQLException::createLangArr(
                'Use method "addSet()"',
                'Используйте метод "addSet()"'
            ));

        try {
            return sprintf(
                'UPDATE %s %s %s;',
                $renderTable, $renderSet, $renderWhere
            );
        } catch (Exception $e) {
            exit('error!');
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finally потому что нам
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
            throw new SQLException(SQLException::createLangArr(
                'Use method "addTable()"',
                'Используйте метод "addTable()"'
            ));
        elseif ($renderWhere == '')
            throw new SQLException(SQLException::createLangArr(
                'Use method "addWhere()"',
                'Используйте метод "addWhere()"'
            ));

        try {
            return sprintf(
                'DELETE FROM %s %s;',
                $renderTable, $renderWhere
            );
        } catch (Exception $e) {
            throw $e;
        } finally {
            // Проверка на чистку таблиц.
            // Сделано в finally потому что нам
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
        unset($this->joinCollection);
    }
}