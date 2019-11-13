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
}