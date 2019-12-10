<?php

declare(strict_types = 1);


namespace SQLBuild;


/**
 * Class SQLType - примитивные типы данных
 * @package SQLBuild
 */
final class SQLType
{
    public const AUTO   = -1;
    public const STRING = 1;
    public const INT    = 2;
    public const BOOL   = 3;
    public const ARG    = 4;
    public const NULL   = 5;
    public const NOEDIT = 6;

    /**
     * Метод форматирует строку как String аргумент
     * MySQL запроа
     *
     * @param String $str
     * @return String
     */
    public static function stringArg($str): String
    {
        return str_replace('"', '\\"', str_replace('\\', '\\\\', (string)$str));
    }
}