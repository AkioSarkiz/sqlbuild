<?php

declare(strict_types=1);


namespace SQLBuild;


final class ColumnCollection extends AbstractCollection
{
    public function __construct(String ...$objs){ $this->objs = $objs; }


    /**
     * Отображение колекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        return '`' . implode($this->objs, '`,`') . '`';
    }
}