<?php

declare(strict_types = 1);


namespace SQLBuild;


final class GroupByCollection extends AbstractCollection
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
     * Отображение коллекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        return 'GROUP BY `' . implode('`,`', $this->objs) . '`';
    }
}