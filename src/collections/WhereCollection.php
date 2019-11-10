<?php

namespace SQLBuild;


final class WhereCollection extends AbstractCollection
{
	public function __construct(array $objs) { $this->objs = $objs; }


    /**
     * Для SELECT|INSERT метода
     *
     * Работает с масивом arrWhere, который содержит в сеье объекты Where.
     * Преобразует массив в отформативаную строку.
     *
     * @see Where
     * @see WhereCollection::$objs
     * @return String
     */
	public function render (): String
	{
	    $temp = 'WHERE';

        foreach ($this->objs as $obj)
        {
            $forTest4length = substr($temp, strlen($temp)-4, 4);
            $forTest3length = substr($temp, strlen($temp)-3, 3);

            if ($forTest4length == ' AND' || $forTest3length == ' OR' || $forTest4length == 'HERE')
                $temp .= $obj->render();
            else
                throw new \Exception('error operator: ' . $forTest4length);
        }

        
        return $temp;
	}
}