<?php

declare(strict_types = 1);


namespace SQLBuild;


final class SQLType
{
    public const AUTO = -1;
    public const STRING = 1;
    public const INT = 2;
    public const BOOL = 3;
    public const ARG = 4;

    /**
     * Проверка строки как аргумента SQL запроса
     * типа String
     *
     * @param String $str
     * @return String
     */
    public static function validStr(String $str): String
    {
        return str_replace('"', '\\"', str_replace('\\', '\\\\', $str));
    }
}