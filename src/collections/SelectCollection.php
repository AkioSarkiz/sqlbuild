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
    const ALL = '--@--@--@--';

    /** @var array */
    private $objs;

    public function __construct(array $objs){ $this->objs = $objs; }

    /**
     * Отображение колекции для SQL запроса
     * @return String
     */
	public function render(): String 
	{
		if ($this->objs[0] == self::ALL || count($this->objs) == 0) {
		    return '*';
		}
		return '`' . implode($this->objs, '`,`') . '`';
	}
}