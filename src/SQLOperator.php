<?php

declare(strict_types = 1);


namespace SQLBuild;


/**
 * Class SQLOperator - операторы MySQL
 * @package SQLBuild
 */
final class SQLOperator
{
    public const NONE   = 0;
    public const AND    = 1;
    public const OR     = 2;
    public const ASC    = 3;
    public const DESC   = 4;
    public const INNER  = 5;
    public const LEFT   = 6;
    public const RIGHT  = 7;
    public const FULL   = 8;
}