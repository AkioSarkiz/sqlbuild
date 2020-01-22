<?php

declare(strict_types=1);


namespace SQLBuild;


use Exception;


/**
 * Class ValueCollection - коллекция с значениями для UPDATE
 * @package SQLBuild
 */
final class ValueCollection extends AbstractCollection
{
	private $temp;

	public function __construct(array $objs){ $this->objs = $objs; }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     * @throws Exception
     */
	public function render(): String
	{
        if (count($this->objs) === 0)
            return '';

		foreach ($this->objs as $obj) {
            try {
                $this->temp .= $obj->render() . ',';
            } catch (Exception $e) {
                throw $e;
            }
        }
		return substr($this->temp, 0, -1);
	}
}