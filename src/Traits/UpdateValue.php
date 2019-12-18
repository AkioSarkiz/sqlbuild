<?php declare(strict_types=1);


namespace SQLBuild\Traits;


trait UpdateValue
{
    /**
     * @param $link_value
     * @param $new_value
     * @return Void
     * @throws \Exception
     */
    protected static function updateValueTrait(&$link_value, $new_value): Void
    {
        if (is_string($link_value) || is_null($link_value) || is_int($link_value) || is_bool($link_value)) {
            $link_value = $new_value;
        } else {
            throw new \Exception('тип аргумента неверный: ' . gettype($new_value));
        }
    }
}