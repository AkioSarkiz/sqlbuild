<?php

declare(strict_types=1);


namespace SQLBuild;

/**
 * Class ColumnCollection - установка колонок для UPDATE
 * @package SQLBuild
 */
final class ColumnCollection extends AbstractCollection
{
    public function __construct(array $objs){ $this->objs = $objs; }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
    public function render(): String
    {
        return '(`' . implode($this->objs, '`,`') . '`)';
    }
}