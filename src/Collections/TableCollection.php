<?php declare(strict_types=1);


namespace SQLBuild;


/**
 * Class TableCollection - коллекция таблиц для SELECT|UPDATE|INSERT
 * @package SQLBuild
 */
final class TableCollection extends AbstractCollection
{
    /**
     * Добавление строки к уже существующей коллекции
     * @param String|array $args
     * @return Void
     */
    public function add($args): Void {
        if (!is_array($args))
            $args = [$args];
        foreach ($args as $arg)
        {
            if (is_string($arg) || is_int($arg))
            {
                array_push($this->objs, (string)$arg);
            } else {
                throw new \InvalidArgumentException('тип аргумента не строка или число, тип: ' . gettype($arg));
            }
        }
    }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        if (count($this->objs) === 0)
            return '';
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