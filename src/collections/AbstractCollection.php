<?php

declare(strict_types = 1);


namespace SQLBuild;


abstract class AbstractCollection
{
    /** @var array */
    protected $objs;

    /**
     * Отображение колекции для SQL запроса
     * @return String
     */
    abstract public function render(): String;
}