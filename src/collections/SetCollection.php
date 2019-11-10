<?php

namespace SQLBuild;

final class SetCollection extends AbstractCollection
{
	public function __construct(array $objs){ $this->objs = $objs; }

	public function render(): String
	{
		$temp = 'SET';
		foreach ($this->objs as $obj) {
			$temp .= $obj->render() . ',';
		}
		return substr($temp, 0, -1);
	}
}