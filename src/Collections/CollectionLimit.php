<?php


namespace SQLBuild;


/**
 * Class CollectionLimit - коллекция для установки лимита для SELECT
 * @package SQLBuild
 */
final class CollectionLimit extends AbstractCollection
{
    /** @var int */
    private $max;
    /** @var string */
    private $start;

    public function __construct($max, $start = null)
    {
        $this->max = (is_string($max)) ? (int)$max : (is_bool($max)) ? -1 : (is_int($max)) ? $max : -1;
        $this->start = ($start) ? $start . ',' : '';
    }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        return 'LIMIT ' .  $this->start . $this->max;
    }
}