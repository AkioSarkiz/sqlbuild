<?php

declare(strict_types = 1);


namespace SQLBuild;


class GroupByCollection extends AbstractCollection
{

    public function __construct($input)
    {
        if (is_array($input))
            $this->objs = $input;
        if (is_string($input) || is_int($input))
            $this->objs =[(String)$input];
        else
            ['-1'];
    }

    /**
     * Отображение колекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        var_dump($this->objs);
        var_dump('GROUP BY `' . implode('`,`', $this->objs) . '` ');
        return 'GROUP BY `' . implode('`,`', $this->objs) . '`';
    }
}