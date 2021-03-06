<?php


namespace SQLBuild;


/**
 * Class AbstractCollection - основа всех коллекций
 * @package SQLBuild
 */
abstract class AbstractCollection
{
    /** @var array */
    protected $objs = [];

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
    abstract public function render(): String;
}