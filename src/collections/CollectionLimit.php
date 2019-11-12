<?php


namespace SQLBuild;


class CollectionLimit extends AbstractCollection
{
    private $max;

    public function __construct($max)
    {
        $this->max = (is_string($max)) ? (int)$max : (is_bool($max)) ? -1 : (is_int($max)) ? $max : -1;
    }

    /**
     * Отображение колекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        return 'LIMIT ' . $this->max;
    }
}