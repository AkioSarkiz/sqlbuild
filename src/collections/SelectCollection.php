<?php

declare(strict_types=1);


namespace SQLBuild;


use Exception;


/**
 * Class SelectCollection - коллекция чтоб выбирать колонок для SELECT
 * @package SQLBuild
 */
final class SelectCollection
{
    /** @var array */
    private $objs;

    public function __construct(array $objs){ $this->objs = $objs; }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
	public function render(): String 
	{
		if (count($this->objs) === 0) {
		    return '*';
		}
		return '`' . implode($this->objs, '`,`') . '`';
	}
}