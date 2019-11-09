<?php

namespace SQLBuild;

final class SetCollection extends AbstractCollection
{
	public function __construct(Set ...$index){ $this->objs = $index; }

	public function render(): String
	{
		$temp = null;
		foreach ($this->objs as $obj) {
			$temp .= $obj->render() . ',';
		}
		return substr($temp, 0, -1);
	}
}