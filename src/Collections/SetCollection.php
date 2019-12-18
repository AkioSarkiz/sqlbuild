<?php


namespace SQLBuild;


/**
 * Class SetCollection - коллекция установки новых значений для UPDATE
 * @package SQLBuild
 */
final class SetCollection extends AbstractCollection
{
	public function __construct(array $objs){ $this->objs = $objs; }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     */
	public function render(): String
	{
		$temp = 'SET';
		foreach ($this->objs as $obj) {
			$temp .= $obj->render() . ',';
		}
		return substr($temp, 0, -1);
	}
}