<?php


namespace SQLBuild;


/**
 * Class TableCollection - коллекция таблиц для SELECT|UPDATE|INSERT
 * @package SQLBuild
 */
final class TableCollection extends AbstractCollection
{
    public function __construct(array $objs){ $this->objs =  $objs; }

    /**
     * Отображение колекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        return '`' . implode($this->objs, '`,`') . '`';
    }

    /**
     * Количесвто таблиц
     * @return int
     */
    public function count(): int
    {
        return count($this->objs);
    }
}