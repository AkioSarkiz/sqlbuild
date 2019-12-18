<?php declare(strict_types=1);


namespace SQLBuild\Traits;


use SQLBuild\SQLType;

trait UpdateType
{
    /**
     * @param $link_type
     * @param $new_type
     * @return Void
     * @throws \Exception
     */
    protected static function updateTypeTrait(&$link_type, $new_type): Void
    {
        if (is_int($new_type)) {
            if (SQLType::isValid($new_type)) {
                $link_type = $new_type;
            } else {
                throw new \Exception('нет такой константы: ' . $new_type);
            }
        } else {
            throw new \Exception('тип аргумента неверный: ' . gettype($new_type));
        }
    }
}