<?php

namespace SQLBuild;


final class TableCollection extends AbstractCollection
{
    public function __construct(array $objs){ $this->objs =  $objs; }

    /**
     * Для SELECT|INSERT метода
     *
     * Работает с масивом objs и возвращает отфармативоную строку
     * @see SQLG::$objs
     * @return String
     */
    public function render(): String
    {
        return '`' . implode($this->objs, '`,`') . '`';
    }

    public function count(): int
    {
        return count($this->objs);
    }
}