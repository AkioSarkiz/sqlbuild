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
            $temp .= $obj->render();
        
        return $temp;
	}
}