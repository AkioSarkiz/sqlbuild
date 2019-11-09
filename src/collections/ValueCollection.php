<?php

declare(strict_types=1);

namespace SQLBuild;

final class ValueCollection extends AbstractCollection
{
	private $temp;

	public function __construct(Value ...$objs){ $this->objs = $objs; }

	public function render(): String
	{
		foreach ($this->objs as $obj) {
			$this->temp .= $obj->render();
		}
		return $this->temp;
	}
}