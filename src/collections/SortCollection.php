<?php

declare(strict_types = 1);


namespace SQLBuild;


final class SortCollection extends AbstractCollection
{
    private $type;

    /**
     * SortCollection constructor.
     * SQLOperator::ASC - убывание
     * SQLOperator::DESC - возрастание
     *
     * @param array $strings
     * @param int $sort
     */
    public function __construct(array $strings, int $sort = SQLOperator::DESC)
    {
        $this->objs = $strings;
        $this->type = (SQLOperator::ASC == $sort) ? 'ASC' : 'DESC';
    }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        return (count($this->objs) == 0) ?  '' :
            'ORDER BY `' . implode($this->objs, '`,`') . '` ' . $this->type;
    }
}