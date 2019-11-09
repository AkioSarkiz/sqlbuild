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
		if ($this->objs[0] == self::ALL) {
			if (count($this->objs) == 1) {
				return ' * ';
			}else{
				throw new Exception("Error Processing Request", 1);
			}
		}
		return '`' . implode($this->objs, '`,`') . '`';
	}
}