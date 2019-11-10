<?php

declare(strict_types=1);


namespace SQLBuild;


use Exception;


final class SelectCollection
{
    const ALL = '--@--@--@--';

    /** @var array */
    private $objs;

    public function __construct(array $objs){ $this->objs = $objs; }

    /**
     * @return String
     * @throws Exception
     */
	public function render(): String 
	{
		if ($this->objs[0] == self::ALL || count($this->objs) == 0) {
		    return '*';
		}
		return '`' . implode($this->objs, '`,`') . '`';
	}
}