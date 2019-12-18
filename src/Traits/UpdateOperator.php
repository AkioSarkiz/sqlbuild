<?php declare(strict_types=1);


namespace SQLBuild\Traits;


use Exception;
use SQLBuild\SQLOperator;


trait UpdateOperator
{
    /**
     * @param $link_oprt
     * @param $new_oprt
     * @return Void
     * @throws Exception
     */
    protected static function updateOperatorTrait(&$link_oprt, $new_oprt): Void
    {
        if (is_int($new_oprt)) {
            if (SQLOperator::isValid($new_oprt)) {
                $link_oprt = $new_oprt;
            } else {
                throw new Exception('нет такой константы: ' . $new_oprt);
            }
        } else {
            throw new Exception('тип аргумента неверный: ' . gettype($new_oprt));
        }
    }
}