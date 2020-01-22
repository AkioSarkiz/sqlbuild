<?php


namespace SQLBuild;


use Exception;


/**
 * Class WhereCollection - коллекция для задания фильтров запроса UPDATE|SELECT
 * @package SQLBuild
 */
final class WhereCollection extends AbstractCollection
{
	public function __construct(array $objs) { $this->objs = $objs; }

    /**
     * Отображение коллекции для SQL запроса
     * @return String
     * @throws Exception
     */
	public function render (): String
	{
	    if (count($this->objs) === 0)
	        return '';

	    $temp = 'WHERE';

        foreach ($this->objs as $obj)
        {
            $forTest4length = substr($temp, strlen($temp)-4, 4);
            $forTest3length = substr($temp, strlen($temp)-3, 3);

            if ($forTest4length == ' AND' || $forTest3length == ' OR' || $forTest4length == 'HERE')
                $temp .= $obj->render();
            else
                throw new Exception('error operator: ' . $forTest4length);
        }

        return $temp;
	}
}